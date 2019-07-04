<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 18:16
 */

namespace CloudsDotEarth\StepByStep;

class Server
{
    /**
     * @var Bundle[]
     */
    public $bundles = [];

    private $main_bundle;

    public function __construct(Bundle &$main_bundle, $swoole_server) {
        $this->main_bundle = $main_bundle;
        $swoole_server->on('start', function ($server) {
            go(function () use(&$server) {
                foreach ($this->main_bundle->controllers_methods as $e) {
                    if ($e["packet_type"] !== "start") continue;
                    if ($this->exec($e["controller"], $e["function"], function ($e, $server) {
                        return [true, null];
                    }, new ControllerMethodArguments(), [], $server)) break;
                }
            });
        });
        $swoole_server->on('request', function ($request, $response) {
            go(function() use (&$request, &$response) {
                foreach ($this->main_bundle->controllers_methods as $e) {
                    if ($e["packet_type"] !== 'request') continue;
                    if ($this->exec($e["controller"], $e["function"], function ($e, $request, $response) {
                        $pattern = $e["match"];
                        $matches = [];
                        if (preg_match_all("^$pattern^", $request->server["request_uri"], $matches))
                            return [true, $matches];
                        return [false, null];
                    }, new ControllerMethodArguments(), [], $request, $response)) break;
                }
            });
        });
        $swoole_server->on('open', function($server, $req) {
            go(function() use (&$server, &$req) {
                echo "connection open: {$req->fd}\n";
                foreach ($this->main_bundle->controllers_methods as $e) {
                    if ($e["packet_type"] !== 'open') continue;
                    if ($this->exec($e["controller"], $e["function"], function ($e, $server, $req) {
                        return [true, null];
                    }, new ControllerMethodArguments(), [], $server, $req)) break;
                }
            });
        });
        $swoole_server->on('message', function($server, $frame) {
            go(function() use (&$server, &$frame) {
                echo "received message: {$frame->data}\n";
                $server->push($frame->fd, json_encode(["hello", "world"]));
                foreach ($this->main_bundle->controllers_methods as $e) {
                    if ($e["packet_type"] !== 'message') continue;
                    if ($this->exec($e["controller"], $e["function"], function ($e, $server, $frame) {
                        $pattern = $e["match"];
                        $matches = [];
                        if (preg_match_all("^$pattern^", $frame->data, $matches))
                            return [true, $matches];
                        return [false, null];
                    }, new ControllerMethodArguments(), [], $server, $frame)) break;
                }
            });
        });
        $swoole_server->on('close', function($server, $fd) {
            go(function () use(&$server, &$fd) {
                echo "connection close: {$fd}\n";
                foreach ($this->main_bundle->controllers_methods as $e) {
                    if ($e["packet_type"] !== 'close') continue;
                    if ($this->exec($e["controller"], $e["function"], function ($e, $server, $fd) {
                        return [true, null];
                    }, new ControllerMethodArguments(), [], $server, $fd)) break;
                }
            });
        });
        $swoole_server->start();
    }

    /**
     * @param string $controller
     * @param string $function
     * @param callable $match_callback
     * @param ControllerMethodArguments $given_target_args
     * @param mixed ...$args
     * @return bool
     * @throws \Exception
     */
    public function exec(string $controller, string $function, callable $match_callback, ControllerMethodArguments $given_target_args, $cross_call_memory, ... $args) : bool {
        var_dump($function);
        $controller_instance = Utils::getController($this->main_bundle->controllers, $controller);
        $method_instance = Utils::getControllerMethod($this->main_bundle->controllers_methods, $controller, $function);
        // no controller was found
        if (is_null($controller_instance)) {
            throw new \Exception("unable to find controller called '$controller'");
        }
        // check if current method match
        [$should_stop, $matches] = $match_callback($method_instance, ... $args);

        if (count($given_target_args->given_matches) > 0)
            $matches = $given_target_args->given_matches;

        if (count($given_target_args->events) > 0) {
            // todo : write me please
        }

        if (count($given_target_args->ignore_events) > 0) {
            // todo : write me please
        }

        if (!$should_stop) {
                return false;
        }
        $ignore_stop = false;
        if ($given_target_args->proceed_before) {
            if ($given_target_args->proceed_hooks) {
                // 01
                foreach ($this->main_bundle->controllers_methods as $method) {
                    foreach ($method["handle this method by ignoring its stop before target ones by ignoring their stop"] as $target_and_args) {
                        [$target, $target_args] = $target_and_args;
                        if ($target === "$controller::$function") {
                            if (!$ignore_stop) {
                                $ignore_stop = true;
                            }
                            [$target_controller_name, $target_function] = [get_class($method["controller"]), $method["this_one"]] ;
                            /**
                             * @var ControllerMethodArguments $target_args
                             */
                            if ($target_args->inherit_match)
                                $target_args->given_matches = $matches;
                            // avoid infinite loop
                            if ($target !== "$target_controller_name::$target_function")
                                $this->exec($target_controller_name, $target_function, $match_callback, $target_args,   ... $args);
                        }
                    }
                }

                // 02
                foreach ($this->main_bundle->controllers_methods as $method) {
                    foreach ($method["handle this method by ignoring its stop before target ones"] as $target_and_args) {
                        [$target, $target_args] = $target_and_args;
                        if ($target === "$controller::$function") {
                            if (!$ignore_stop) {
                                $ignore_stop = true;
                            }
                            [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]] ;
                            /**
                             * @var ControllerMethodArguments $target_args
                             */
                            if ($target_args->inherit_match)
                                $target_args->given_matches = $matches;
                            // avoid infinite loop
                            if ($target !== "$target_controller::$target_function")
                                if ($this->exec($target_controller, $target_function, $match_callback, $target_args,   ... $args))
                                    return true;
                        }
                    }
                }
            }
            if ($given_target_args->proceed_inner_events) {
                // 03
                foreach ($method_instance["handle target methods by ignoring their stop before this one by ignoring its stop"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    // avoid infinite loop
                    if ($target !== "$controller::$function") {
                        if (!$ignore_stop) {
                            $ignore_stop = true;
                        }
                        [$target_controller, $target_function] = explode("::",$target ) ;
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        $this->exec($target_controller, $target_function, $match_callback, $target_args,   ... $args);

                    }
                }
            }
            if ($given_target_args->proceed_hooks) {

                // 04
                foreach ($this->main_bundle->controllers_methods as $method) {
                    foreach ($method["handle this method before target ones by ignoring their stop"] as $target_and_args) {
                        [$target, $target_args] = $target_and_args;
                        if ($target === "$controller::$function") {
                            [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]];
                            /**
                             * @var ControllerMethodArguments $target_args
                             */
                            if ($target_args->inherit_match)
                                $target_args->given_matches = $matches;
                            // avoid infinite loop
                            if ($target !== "$target_controller::$target_function")
                                $this->exec($target_controller, $target_function, $match_callback, $target_args, ... $args);
                        }
                    }
                }
            }
            if ($given_target_args->proceed_inner_events) {
                // 05
                foreach ($method_instance["handle target methods by ignoring their stop before this one"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    // avoid infinite loop
                    if ($target !== "$controller::$function") {
                        [$target_controller_name, $target_function] = explode("::",$target ) ;
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        $this->exec($target_controller_name, $target_function, $match_callback, $target_args,   ... $args);
                    }
                }
            }
            if ($given_target_args->proceed_hooks) {
                // 06
                foreach ($this->main_bundle->controllers_methods as $method) {
                    foreach ($method["handle this method before target ones"] as $target_and_args) {
                        [$target, $target_args] = $target_and_args;
                        if ($target === "$controller::$function") {
                            [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]];
                            /**
                             * @var ControllerMethodArguments $target_args
                             */
                            if ($target_args->inherit_match)
                                $target_args->given_matches = $matches;
                            // avoid infinite loop
                            if ($target !== "$target_controller::$target_function")
                                if ($this->exec($target_controller, $target_function, $match_callback, $target_args, ... $args))
                                    return true;
                        }
                    }
                }
            }
            if ($given_target_args->proceed_inner_events) {
                // 07
                foreach ($method_instance["handle target methods before this one by ignoring its stop"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    // avoid infinite loop
                    if ($target !== "$controller::$function") {
                        if (!$ignore_stop) {
                            $ignore_stop = true;
                        }
                        [$target_controller_name, $target_function] = explode("::", $target);
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        // avoid infinite loop
                        if ($target !== "$target_controller_name::$target_function")
                            if ($this->exec($target_controller_name, $target_function, $match_callback, $target_args, ... $args))
                                return true;
                    }
                }
                // 08
                foreach ($method_instance["handle target methods before this one"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    /**
                     * @var ControllerMethodArguments $target_args
                     */
                    if ($target_args->inherit_match)
                        $target_args->given_matches = $matches;
                    // avoid infinite loop
                    if ($target !== "$controller::$function") {
                        [$target_controller_name, $target_function] = explode("::", $target);
                        if ($target !== "$target_controller_name::$target_function")
                            if ($this->exec($target_controller_name, $target_function, $match_callback, $target_args, ... $args))
                                return true;
                    }
                }
            }
        }

        // try to call requested function
        if ($controller_instance->$function(... array_merge($args, [&$cross_call_memory, &$this->main_bundle, &$matches]))) {
            if ($given_target_args->proceed_after) {
                // 09
                if ($given_target_args->proceed_hooks) {
                    foreach ($this->main_bundle->controllers_methods as $method) {
                        foreach ($method["handle this method after target ones by ignoring their stop"] as $target_and_args) {
                            [$target, $target_args] = $target_and_args;
                            if ($target === "$controller::$function") {
                                [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]] ;
                                /**
                                 * @var ControllerMethodArguments $target_args
                                 */
                                if ($target_args->inherit_match)
                                    $target_args->given_matches = $matches;
                                // avoid infinite loop
                                if ($target !== "$target_controller::$target_function")
                                    $this->exec($target_controller, $target_function, $match_callback, $target_args,   ... $args);
                            }
                        }
                    }
                }
                if ($given_target_args->proceed_inner_events) {
                    //10
                    foreach ($method_instance["handle target methods after this one by ignoring its stop"] as $target_and_args) {
                        [$target, $target_args] = $target_and_args;
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        // avoid infinite loop
                        if ($target !== "$controller::$function") {
                            if (!$ignore_stop) {
                                $ignore_stop = true;
                            }
                            [$target_controller, $target_function] = explode("::",$target ) ;
                            if ($target !== "$target_controller::$target_function")
                                if ($this->exec($target_controller, $target_function, $match_callback, $target_args,   ... $args))
                                    return true;
                        }
                    }
                }
            }

            if (!$ignore_stop) {
                return true;
            }
        }
        if ($given_target_args->proceed_hooks) {
            //11
            foreach ($this->main_bundle->controllers_methods as $method) {
                foreach ($method["handle this method by ignoring its stop after target ones by ignoring their stop"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    if ($target === "$controller::$function") {
                        if (!$ignore_stop) {
                            $ignore_stop = true;
                        }
                        [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]];
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        // avoid infinite loop
                        if ($target !== "$target_controller::$target_function")
                            $this->exec($target_controller, $target_function, $match_callback, $target_args, ... $args);
                    }
                }
            }
        }
        if ($given_target_args->proceed_inner_events) {

            // 12
            foreach ($method_instance["handle target methods by ignoring their stop after this one by ignoring its stop"] as $target_and_args) {
                [$target, $target_args] = $target_and_args;
                // avoid infinite loop
                if ($target !== "$controller::$function") {
                    if (!$ignore_stop) {
                        $ignore_stop = true;
                    }
                    [$target_controller_name, $target_function] = explode("::", $target);
                    /**
                     * @var ControllerMethodArguments $target_args
                     */
                    if ($target_args->inherit_match)
                        $target_args->given_matches = $matches;
                    $this->exec($target_controller_name, $target_function, $match_callback, $target_args, ... $args);
                }
            }


            // 13
            foreach ($method_instance["handle target methods by ignoring their stop after this one"] as $target_and_args) {
                [$target, $target_args] = $target_and_args;
                // avoid infinite loop
                if ($target !== "$controller::$function") {
                    [$target_controller_name, $target_function] = explode("::", $target);
                    /**
                     * @var ControllerMethodArguments $target_args
                     */
                    if ($target_args->inherit_match)
                        $target_args->given_matches = $matches;
                    $this->exec($target_controller_name, $target_function, $match_callback, $target_args, ... $args);
                }
            }
        }
        if ($given_target_args->proceed_hooks) {
            // 14
            foreach ($this->main_bundle->controllers_methods as $method) {
                foreach ($method["handle this method by ignoring its stop after target ones"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    if ($target === "$controller::$function") {
                        if (!$ignore_stop) {
                            $ignore_stop = true;
                        }
                        [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]];
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        // avoid infinite loop
                        if ($target !== "$target_controller::$target_function")
                            if ($this->exec($target_controller, $target_function, $match_callback, $target_args, ... $args))
                                return true;
                    }
                }
            }
        }
        if ($given_target_args->proceed_inner_events) {
            // 15
            foreach ($method_instance["handle target methods after this one"] as $target_and_args) {
                [$target, $target_args] = $target_and_args;
                var_dump("target methods after this one");
                // avoid infinite loop
                if ($target !== "$controller::$function") {
                    [$target_controller, $target_function] = explode("::", $target);
                    /**
                     * @var ControllerMethodArguments $target_args
                     */
                    if ($target_args->inherit_match)
                        $target_args->given_matches = $matches;
                    if ($target !== "$target_controller::$target_function")
                        if ($this->exec($target_controller, $target_function, $match_callback, $target_args, ... $args))
                            return true;
                }
            }
        }
        if ($given_target_args->proceed_hooks) {
            // 16
            foreach ($this->main_bundle->controllers_methods as $method) {
                foreach ($method["handle this method after target ones"] as $target_and_args) {
                    [$target, $target_args] = $target_and_args;
                    if ($target === "$controller::$function") {
                        [$target_controller, $target_function] = [get_class($method["controller"]), $method["this_one"]];
                        /**
                         * @var ControllerMethodArguments $target_args
                         */
                        if ($target_args->inherit_match)
                            $target_args->given_matches = $matches;
                        // avoid infinite loop
                        if ($target !== "$target_controller::$target_function")
                            if ($this->exec($target_controller, $target_function, $match_callback, $target_args, ... $args))
                                return true;
                    }
                }
            }
        }
        return false;
    }
}