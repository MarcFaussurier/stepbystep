<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 18:16
 */

namespace CloudsDotEarth\StepByStep;

class Controller
{
    /**
     * Controller constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->setMetaData();
    }

    /**
     * Format is function name, uri regex, service regex, priority, controller
     * @var array[]
     */
    public $methods = [];

    /**
     * Will set $methods array using reflection // tokens parsing
     * @throws \ReflectionException
     */
    public function setMetaData() : void {
        var_dump("instanciating a controller");
        $class_info = new \ReflectionClass($this);
        $source = file_get_contents($class_info->getFileName());
        $tokens = token_get_all( $source );
        $output = [];
        $lastComment = "";
        foreach ($tokens as $token) {
            // don't proceed useless tokens
            if (in_array($token[0], [
                T_COMMENT,      // All comments since PHP5
                T_DOC_COMMENT,  // PHPDoc comments
                T_STRING
            ])) {
                if (($lastComment !== "") && ($token[0] === T_STRING)) {
                    $lastFunction = $token[1];
                    if ((strpos($lastComment, "@packet_type ") !== false)) {
                        $exploded = explode("@packet_type ", $lastComment)[1];
                        $packet_type = explode("\n",$exploded)[0];
                        $priority = 0;
                        $match = true;
                        $this_one = "";
                        // 0 0
                        $aa = [];
                        $ab = [];
                        $ac = [];
                        $ad = [];
                        // 1 0
                        $ba = [];
                        $bb = [];
                        $bc = [];
                        $bd = [];
                        // 0 1
                        $ca = [];
                        $cb = [];
                        $cc = [];
                        $cd = [];
                        // 1 1
                        $da = [];
                        $db = [];
                        $dc = [];
                        $dd = [];
                        if ((strpos($lastComment, "@priority ") !== false)) {
                            $exploded = explode("@priority ", $lastComment)[1];
                            $priority = explode("\n", $exploded)[0];
                        }
                        if ((strpos($lastComment, "@this_one ") !== false)) {
                            $exploded = explode("@this_one ", $lastComment)[1];
                            $this_one = explode("\n", $exploded)[0];
                        }
                        if ((strpos($lastComment, "@match ") !== false)) {
                            $exploded = explode("@match ", $lastComment)[1];
                            $match = explode("\n", $exploded)[0];
                        }
                        if ((strpos($lastComment, "@handle ") !== false)) {
                            var_dump("found handle");

                            $exploded = explode("@handle ", $lastComment);
                            unset($exploded[0]);

                            foreach ($exploded as $v) {
                                $lines = explode(PHP_EOL,  $v);
                                $type = trim($lines[0]);
                                unset($lines[0]);
                                $elements = [];
                                foreach ($lines as $k =>  $e) {
                                    $arguments = trim(explode("*", trim(explode("🎯", $e)[0]))[1]);
                                    $controller_method_args = new ControllerMethodArguments($arguments);
                                    if (!isset(explode("🎯", $e)[1]))
                                        break;
                                    $elements[$k] = [trim(explode("🎯", $e)[1]), $controller_method_args];
                                    // $elements[$k] = trim(explode("🎯", $e)[1]);
                                }
                                switch ($type) {
                                    case "target methods after this one":
                                        $aa = array_merge($aa, $elements);      break;
                                    case "target methods before this one":
                                        $ab = array_merge($ab, $elements);      break;
                                    case "this method after target ones":
                                        $ac = array_merge($ac, $elements);      break;
                                    case "this method before target ones":
                                        $ad = array_merge($ad, $elements);      break;
                                    case "target methods after this one by ignoring its stop":
                                        $ba = array_merge($ba, $elements);      break;
                                    case "target methods before this one by ignoring its stop":
                                        $bb = array_merge($bb, $elements);      break;
                                    case  "this method by ignoring its stop after target ones":
                                        $bc = array_merge($bc, $elements);      break;
                                    case "this method by ignoring its stop before target ones":
                                        $bd = array_merge($bd, $elements);      break;
                                    case "target methods by ignoring their stop after this one":
                                        $ca = array_merge($ca, $elements);      break;
                                    case "target methods by ignoring their stop before this one":
                                        $cb = array_merge($cb, $elements);      break;
                                    case "this method after target ones by ignoring their stop":
                                        $cc = array_merge($cc, $elements);      break;
                                    case "this method before target ones by ignoring their stop":
                                        $cd = array_merge($cd, $elements);      break;
                                    case "target methods by ignoring their stop after this one by ignoring its stop":
                                        $da = array_merge($da, $elements);      break;
                                    case "target methods by ignoring their stop before this one by ignoring its stop":
                                        $db = array_merge($db, $elements);      break;
                                    case "this method by ignoring its stop after target ones by ignoring their stop":
                                        $dc = array_merge($dc, $elements);      break;
                                    case "this method by ignoring its stop before target ones by ignoring their stop":
                                        $dd = array_merge($dd, $elements);      break;
                                    default:  break;
                                }
                            }
                        }

                        $output[] = [
                            "this_one"                                                      => trim($this_one),
                            "controller"                                                    => get_class($this),
                            "function"                                                      => $lastFunction,
                            "packet_type"                                                   => trim($packet_type),
                            "priority"                                                      => trim($priority),
                            "match"                                                         => trim($match),
                            "handle target methods after this one"                                              => $aa,
                            "handle target methods before this one"                                             => $ab,
                            "handle this method after target ones"                                              => $ac,
                            "handle this method before target ones"                                             => $ad,
                            "handle target methods after this one by ignoring its stop"                         => $ba,
                            "handle target methods before this one by ignoring its stop"                        => $bb,
                            "handle this method by ignoring its stop after target ones"                         => $bc,
                            "handle this method by ignoring its stop before target ones"                        => $bd,
                            "handle target methods by ignoring their stop after this one"                       => $ca,
                            "handle target methods by ignoring their stop before this one"                      => $cb,
                            "handle this method after target ones by ignoring their stop"                       => $cc,
                            "handle this method before target ones by ignoring their stop"                      => $cd,
                            "handle target methods by ignoring their stop after this one by ignoring its stop"  => $da,
                            "handle target methods by ignoring their stop before this one by ignoring its stop" => $db,
                            "handle this method by ignoring its stop after target ones by ignoring their stop"  => $dc,
                            "handle this method by ignoring its stop before target ones by ignoring their stop" => $dd,
                        ];
                    }
                    $lastComment = "";
                } else {
                    if ($token[0] === T_DOC_COMMENT) {
                        $lastComment = $token[1];
                    }
                }
            }
        }
        $this->methods = $output;
    }
}