<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 19:21
 */

$GLOBALS["ip"] = "127.0.0.1";
$GLOBALS["port"] = "8080";

return new \swoole_http_server($GLOBALS["ip"], $GLOBALS["port"]);

