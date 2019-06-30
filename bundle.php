<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 18:43
 */

require_once "vendor/autoload.php";

if (isset($argv)) {
    $GLOBALS["argv"] = $argv;
}

$bundle = new \CloudsDotEarth\StepByStep\Bundle(__DIR__);