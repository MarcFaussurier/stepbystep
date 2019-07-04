<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 18:43
 */

/**
 * @param $path
 * @param int $deep
 * @return string
 * @throws \Exception
 */
if (!function_exists("recursive_autoloader_detector")) {
    function recursive_autoloader_detector($path, $deep = 0) {
        if ($deep > 10)
            throw new Exception("Unable to detect autoload.php... exiting");
        else if (file_exists("$path/vendor/autoload.php"))
            return "$path/vendor/autoload.php";
        else
            return recursive_autoloader_detector("$path/../", ++$deep);
    };
}
try {
    require_once recursive_autoloader_detector(__DIR__ );
    if (isset($argv))
        $GLOBALS["argv"] = $argv;
    if (isset($GLOBALS["main_bundle"]))
        return new \CloudsDotEarth\StepByStep\Bundle
        (   __DIR__,
            null,
            null,
            null  ,
            null,
            $GLOBALS["main_bundle"]);
    else
        return new \CloudsDotEarth\StepByStep\Bundle(__DIR__);
} catch (\Exception $exception) {
    echo 'Caught exception: ',  $exception->getMessage(), "\n";
}