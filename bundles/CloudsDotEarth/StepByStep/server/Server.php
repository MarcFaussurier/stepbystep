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

    public function __construct(Bundle $main_bundle, $swoole_server) {
        $this->main_bundle = $main_bundle;
        $swoole_server->on('start', function ($server) use ($main_bundle) {
            echo "Swoole http server is started at ". $GLOBALS["ip"] .":" . $GLOBALS["port"];
        });
        $swoole_server->on('request', function ($request, $response) use ($main_bundle) {
            echo("inside request match");
            foreach ($main_bundle->controllers_methods as $e) {
                if ($e["packet_type"] !== 'request') break;
                if ($this->exec(get_class($e["controller"]), $e["function"] , function($e, $request, $response) {
                    $pattern = $e["match"];
                    $matches = [];
                    if (preg_match_all("^$pattern^", $request->server["request_uri"], $matches))
                        return [true, $matches];
                    return [false, null];

                }, $request, $response)) break;
        }});
        $swoole_server->on('open', function($server, $req) use ($main_bundle) {
            echo "connection open: {$req->fd}\n";
        });
        $swoole_server->on('message', function($server, $frame) use ($main_bundle) {
            echo "received message: {$frame->data}\n";
            $server->push($frame->fd, json_encode(["hello", "world"]));
        });
        $swoole_server->on('close', function($server, $fd) use ($main_bundle) {
            echo "connection close: {$fd}\n";
        });
        $swoole_server->start();
    }

    /**
     * @param string $controller
     * @param string $function
     * @param callable $match_callback
     * @param mixed ...$args
     * @return bool
     * @throws \Exception
     */
    public function exec(string $controller, string $function, callable $match_callback, ... $args) : bool {
        var_dump($function);
        $controller_instance = Utils::getController($this->main_bundle->controllers, $controller);
        $method_instance = Utils::getControllerMethod($this->main_bundle->controllers_methods, $controller, $function);
        // no controller was found
        if (is_null($controller_instance)) {
            throw new \Exception("unable to find controller called '$controller'");
        }
        // check if current method match
        [$should_stop, $matches] = $match_callback($method_instance, ... $args);
        if (!$should_stop) {
                return false;
        }
        $ignore_stop = false;

        // 01
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method by ignoring its stop before target ones by ignoring their stop"] as $target) {
                if ($target === "$controller::$function") {
                    if (!$ignore_stop) {
                        $ignore_stop = true;
                    }
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        $target_controller->$target_function(... $args);
                        $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);
                }
            }
        }

        // 02
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method by ignoring its stop before target ones"] as $target) {
                if ($target === "$controller::$function") {
                    if (!$ignore_stop) {
                        $ignore_stop = true;
                    }
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                            return true;
                }
            }
        }

        // 03
        foreach ($method_instance["handle target methods by ignoring their stop before this one by ignoring its stop"] as $target) {
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                if (!$ignore_stop) {
                    $ignore_stop = true;
                }
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);

            }
        }
        // 04
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method before target ones by ignoring their stop"] as $target) {
                if ($target === "$controller::$function") {
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);
                }
            }
        }
        // 05
        foreach ($method_instance["handle target methods by ignoring their stop before this one"] as $target) {
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);

            }
        }
        // 06
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method before target ones"] as $target) {
                if ($target === "$controller::$function") {
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                            return true;
                }
            }
        }
        // 07
        foreach ($method_instance["handle target methods before this one by ignoring its stop"] as $target) {
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                if (!$ignore_stop) {
                    $ignore_stop = true;
                }
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $target_controller->$target_function(... $args);
                if ($target !== "$target_controller::$target_function")
                    if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                        return true;
            }
        }
        // 08
        foreach ($method_instance["handle target methods before this one"] as $target) {
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $target_controller->$target_function(... $args);
                if ($target !== "$target_controller::$target_function")
                    if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                        return true;
            }
        }

        // try to call requested function
        if ($controller_instance->$function(... array_merge($args, [$matches]))) {
            // 09
            foreach ($this->main_bundle->controllers_methods as $method) {
                foreach ($method["handle this method after target ones by ignoring their stop"] as $target) {
                    if ($target === "$controller::$function") {
                        [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                        // avoid infinite loop
                        if ($target !== "$target_controller::$target_function")
                            $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);
                    }
                }
            }

            //10
            foreach ($method_instance["handle target methods after this one by ignoring its stop"] as $target) {
                // avoid infinite loop
                if ($target !== "$controller::$function") {
                    if (!$ignore_stop) {
                        $ignore_stop = true;
                    }
                    [$target_controller, $target_function] = explode("::",$target ) ;
                    $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                    $target_controller->$target_function(... $args);
                    if ($target !== "$target_controller::$target_function")
                        if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                            return true;
                }
            }

            if (!$ignore_stop) {
                return true;
            }
        }
        //11
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method by ignoring its stop after target ones by ignoring their stop"] as $target) {
                if ($target === "$controller::$function") {
                    if (!$ignore_stop) {
                        $ignore_stop = true;
                    }
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);
                }
            }
        }

        // 12
        foreach ($method_instance["handle target methods by ignoring their stop after this one by ignoring its stop"] as $target) {
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                if (!$ignore_stop) {
                    $ignore_stop = true;
                }
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);
            }
        }


        // 13
        foreach ($method_instance["handle target methods by ignoring their stop after this one"] as $target) {
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args);
            }
        }

        // 14
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method by ignoring its stop after target ones"] as $target) {
                if ($target === "$controller::$function") {
                    if (!$ignore_stop) {
                        $ignore_stop = true;
                    }
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                            return true;
                }
            }
        }

        // 15
        foreach ($method_instance["handle target methods after this one"] as $target) {
            var_dump("target methods after this one");
            // avoid infinite loop
            if ($target !== "$controller::$function") {
                [$target_controller, $target_function] = explode("::",$target ) ;
                $target_controller = Utils::getController($this->main_bundle->controllers, $target_controller);
                $target_controller->$target_function(... $args);
                if ($target !== "$target_controller::$target_function")
                    if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                        return true;
            }
        }


        // 16
        foreach ($this->main_bundle->controllers_methods as $method) {
            foreach ($method["handle this method after target ones"] as $target) {
                if ($target === "$controller::$function") {
                    [$target_controller, $target_function] = [$method["controller"], $method["this_one"]] ;
                    // avoid infinite loop
                    if ($target !== "$target_controller::$target_function")
                        if ($this->exec(get_class($target_controller), $target_function, $match_callback,   ... $args))
                            return true;
                }
            }
        }

        return false;
    }
}