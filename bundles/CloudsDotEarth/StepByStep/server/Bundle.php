<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 18:16
 */

namespace CloudsDotEarth\StepByStep;

use Symfony\Component\Dotenv\Dotenv;

class Bundle
{
    /**
     * @var Controller[]
     */
    public $controllers = [];
    public $controllers_methods = [];
    public $child_bundles = [];
    /**
     * @var bool
     */
    private $is_main = true;
    private $root_path = "";
    private $relative_controller_root = "/controller";
    private $relative_web_root = "/asset";
    private $relative_server_root = "/server";

    public function __construct($root_path, $relative_controller_root = null, $relative_web_root = null, $relative_server_root = null)
    {
        $this->root_path = $root_path;
        if (file_exists($root_path . "/../../../vendor/autoload.php")) {
            $this->is_main = false;
        } else if (!file_exists($root_path . "/vendor/autoload.php")) {
            throw new \Exception("Unable to find autoloader.php in $root_path. Please run composer install before launching app.php");
        }
        echo "Loading bundle using root path : $root_path is main :  $this->is_main" . PHP_EOL;
        $this->relative_controller_root = $relative_controller_root ?? $this->relative_controller_root;
        $this->relative_web_root = $relative_web_root ?? $this->relative_web_root;
        $this->relative_server_root = $relative_server_root ?? $this->relative_server_root;

        $this->loadControllers();

        if ($this->is_main) {
            $this->init();
        }

        foreach ($this->controllers as $e) {
            foreach ($e->methods as $f) {
                $this->controllers_methods[] = $f;
            }
        }

        usort($this->controllers, function($a, $b) {
            return ($a["priority"] > $b["priority"] );
        });

        $this->loadChildBundles();

        if ($this->is_main)  $this->run();
    }

    public function loadChildBundles() {

    }

    public function init() {
        $dotenv = new Dotenv();
        $dotenv->load("$this->root_path/.env");
        if ( isset($GLOBALS["argv"][1]) && strlen($GLOBALS["argv"][1]) > 0) {
            var_dump($GLOBALS["argv"][1]);

            $_ENV["DEFAULT_SERVER"] = $GLOBALS["argv"][1];
        }
    }

    public function run() {
        new Server($this, require_once "$this->root_path/server/" . $_ENV["DEFAULT_SERVER"] . ".php");
    }

    public function loadControllers() {
        echo "loading controllers ... " . PHP_EOL;
        foreach (glob($this->root_path . $this->relative_controller_root . "/{,*/,*/*/,*/*/*/}*.php", GLOB_BRACE ) as $file) {
            var_dump($file);
            $controller = Utils::getClassNameFromFile($file);
            array_push($this->controllers, new $controller());
        }
    }
}