<?php
/**
 * Created by PhpStorm.
 * User: marcfsr
 * Date: 11/03/2019
 * Time: 20:09
 */
namespace CloudsDotEarth\StepByStep;

class ModelGenerator
{
    public  $output_dir;
    private $innerTables = [];
    private $main_bundle;
    /**
     * ModelGenerator constructor.
     * @param Bundle $main_bundle
     * @param string $output_dir
     * @throws \Exception
     */
    public function __construct(Bundle &$main_bundle, string $output_dir)
    {
        $this->main_bundle = $main_bundle;
        $this->output_dir = $output_dir;
        $tables = Utils::getDatabaseTables($core);
        foreach ($tables as $k => $v) {
            // we don't use table that start with a # as they are for jointures
            if (substr($v, 0, 1) !== "#") {
                array_push($this->innerTables, $v);
                $cols = Utils::getColsInTable($core, $v);
                $this->writePropertiesClass($v, $cols);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function secondStep() {
        foreach ($this->innerTables as $k => $table) {
            $class = Model::tableNameToClass($table);
            $instance = new $class;
            $path = $this->output_dir . "/" . ucfirst($table) . "Properties.php";
            foreach ($instance->relations as $col => $v) {
                // todo : save of one-to-many and many-to-one
                // relation can be one-to-many
                // or many-to-many
                if (in_array($v[0], ["one_to_many", "many_to_many"])) {
                    $a = explode("}", file_get_contents($path));
                    unset($a[count($a) - 1]);
                    $a = join("}", $a);
                    $fileContent = $a;
                    $fileContent .= " 
    /**
    * - In relation with the $col table.
    * @var ".Model::tableNameToClass($col)."[]
    */
    public $$col = [];" . PHP_EOL;
                    $fileContent .= "} ";
                    file_put_contents($path, $fileContent);
                }
            }
        }
    }
    /**
     * @param string $MySQLType
     * @return string
     * @throws \Exception
     */
    public static function MySQLTypeToPHP(string $MySQLType) {
        switch($a = explode("(", $MySQLType)[0]) {
            case "int":
                return "int";
            case "varchar":
                return "string";
            case "text":
                return "string";
            case "datetime":
                return \DateTime::class;
            case "timestamp":
                return \DateTime::class;
            default:
                var_dump($a);
                throw new \Exception("Unknow MySQL type");
                break;
        }
    }
    /**
     * @param string $table
     * @param array $cols
     * @throws \Exception
     */
    public function writePropertiesClass(string $table, array $cols) : void {
        $className = ucfirst($table)."Properties";
        $fileContent = "<?php 
class $className extends \\".Model::class."
{";
        $fileContent .= "
    public \$tableName = '" . trim($table) . "';";
        foreach ($cols as $k => $v) {
            $name = $v["Field"];
            $phpType = self::MySQLTypeToPHP($v["Type"]);
            $null = $v["Null"] === "YES";
            $defaultValue = $v["Default"];
            $fileContent.=  "
    /**
    * @col $name
    * @mysql_type " . $v["Type"] . "
    * @var $phpType
    */
    public $$name";
            $toAdd = $defaultValue !== "NULL" ? $defaultValue : $null ? "null" : "";
            $fileContent .= ($toAdd !== "" ? " = " . $toAdd : "") . ";" . PHP_EOL;
        }
        $fileContent .= "}";
        Utils::filePutsContent
        (
            $this->output_dir . "/$className.php",
            $fileContent
        );
    }
}