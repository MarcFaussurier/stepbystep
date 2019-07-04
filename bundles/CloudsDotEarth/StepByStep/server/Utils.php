<?php

/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 28/06/2019
 * Time: 19:15
 */

namespace CloudsDotEarth\StepByStep;

class Utils {

    /**
     * @param Controller[] $controller_list
     * @param string $controller_name
     * @return Controller
     * @throws \Exception
     */
    static public function getController( $controller_list, string $controller_name): Controller {
        foreach ($controller_list as $controller)
            if ($controller instanceof $controller_name)
                return $controller;
        throw new \Exception("Unable to find $controller_name in given controller list");
    }

    /**
     * @param array[] $method_list
     * @param string $controller_name
     * @param string $function_name
     * @return array
     * @throws \Exception
     */
    static public function getControllerMethod($method_list, $controller_name, $function_name): array {
        $e = null;
        foreach ($method_list as $f) {
            if ($f["this_one"] == $function_name && $f["controller"] === $controller_name)
                $e = $f;
        }
        if (is_null($e)) {
            throw new \Exception("Unable to find $controller_name::$function_name but the controller exists.");
        }
        return $e;
    }

    /**
     * @param string $file_path
     * @return string
     */
    static public function getNamespaceFromFile(string $file_path) : string {
        $ns = NULL;
        $handle = fopen($file_path, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'namespace') === 0) {
                    $parts = explode(' ', $line);
                    $ns = rtrim(trim($parts[1]), ';');
                    break;
                }
            }
            fclose($handle);
        }
        return $ns;
    }

    /**
     * @param string $file_path
     * @param bool $namespaced
     * @return string
     */
    static public function getClassNameFromFile($file_path, bool $namespaced = true) : string {
        $fp = fopen($file_path, 'r');
        $class = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) break;

            $buffer .= fread($fp, 512);
            $tokens = @token_get_all($buffer);

            if (strpos($buffer, '{') === false) continue;

            for (;$i<count($tokens);$i++) {
                if ($tokens[$i][0] === T_CLASS) {
                    for ($j=$i+1;$j<count($tokens);$j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i+2][1];
                        }
                    }
                }
            }
        }
        if (!$namespaced) {
            return $class;
        } else {
            return "\\" . self::getNamespaceFromFile($file_path) . "\\$class";
        }
    }



    /**
     * Returns all table in the current database
     * @param Core $core
     * @return string[]
     */
    public static function getDatabaseTables(Core &$core) : array {
        $output = [];
        $tables = $core->db->query("show tables");
        foreach ($tables as $k => $v) {
            array_push($output, $v["Tables_in_" . $core->envConfig["database"]["database"]]);
        }
        return $output;
    }

    /**
     * @param Core $core
     * @param string $tableName
     * @return array
     */
    public static function getColsInTable(Core &$core, string $tableName) : array {
        return
            $core->db->query("SHOW COLUMNS FROM `". $tableName . "`");
    }

    /**
     *
     * @param string $path
     * @param string $content
     * @param bool $createPath
     */
    public static function filePutsContent(string $path, string $content, bool $createPath = true) {
        if ($createPath) {
            $h = explode("/", $path);
            unset($h[count($h) - 1]);
            $dirPath = join("/", $h);
            if (!is_dir($dirPath)) {
                mkdir(join("/", $h), 0755, true);
            }
        }
        file_put_contents( $path, $content);
    }
}
