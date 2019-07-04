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
        $this->output_dir = $GLOBALS["main_bundle"]->root_path . $GLOBALS["main_bundle"]->relative_model_root;
        $tables = Utils::getDatabaseTables();
        foreach ($tables as $k => $v) {
            // we don't use table that start with a # as they are for jointures
            if (substr($v, 0, 1) !== "#") {
                array_push($this->innerTables, $v);
                $cols = Utils::getColsInTable($v);
                $this->writePropertiesClass($v, $cols);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function secondStep() {
        foreach ($this->innerTables as $k => $table) {
            [$class_name, $properties_class_name, $namespaced_class_name, $namespaced_properties_class_name, $namespace ] = ModelGenerator::getInfosFromTableName($table);

            $class = Model::tableNameToClass($table);
            if (!file_exists($this->output_dir . "/" . ucfirst($table)));
                file_put_contents
                ($this->output_dir . "/" . ucfirst($table) . ".php",

                     "<?php 
namespace $namespace;
class $class_name extends $properties_class_name
{
}"
                    );
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
            case "boolean":
                return "bool";
            case "numeric":
                return "float";
            case "integer":
                return "int";
            case "bigint":
                return "string";
            case "int":
                return "int";
            case "smallint":
                return "int";
            case "varchar":
                return "string";
            case "text":
                return "string";
            case "date":
                return \DateTime::class;
            case "datetime":
                return \DateTime::class;
            case "character":
                return "string";
            case "character varying":
                return "string";
            default:
                throw new \Exception("Unknow MySQL type: $a");
                break;
        }
    }

    public static function getInfosFromTableName($table_name) {
        var_dump($table_name);
        $className =  ucfirst($table_name);
        $propertiesClassName = $className."Properties";
        $namespace = explode("\Controller",
                trim(explode(";",
                    explode("namespace",
                        file_get_contents(
                            glob($GLOBALS["main_bundle"]->root_path . $GLOBALS["main_bundle"]->relative_controller_root . "/*.php")
                            [0])
                    )[1]
                )[0])
            )[0] . "\\" . ucfirst(substr($GLOBALS["main_bundle"]->relative_model_root, 1, strlen($GLOBALS["main_bundle"]->relative_model_root)));
        return [
            0 => $className,
            1 => $propertiesClassName,
            2 => "$namespace\\$className",
            3 => "$namespace\\$propertiesClassName",
            4 => $namespace,
        ];
    }

    /**
     * @param string $table
     * @param array $cols
     * @throws \Exception
     */
    public function writePropertiesClass(string $table, array $cols) : void {
        [$class_name, $properties_class_name, $namespaced_class_name, $namespaced_properties_class_name, $namespace ] = ModelGenerator::getInfosFromTableName($table);
        $file_content = "<?php 
namespace $namespace;
class $properties_class_name extends \\".Model::class."
{";
        $file_content .= "
    public \$table_name = '" . trim($table) . "';";
        foreach ($cols as $k => $v) {
            $name = $v["column_name"];
            $phpType = self::MySQLTypeToPHP($v["data_type"]);
            $null = $v["is_nullable"] === "YES";
            $default_value = $v["column_default"];
            $file_content.=  "
    /**
    * @col $name
    * @mysql_type " . $v["data_type"] . "
    * @var $phpType
    */
    public $$name";
            $to_add = $default_value !== "NULL" ? $default_value : $null ? "null" : "";
            $file_content .= ($to_add !== "" ? " = " . $to_add : "") . ";" . PHP_EOL;
        }
        $file_content .= "}";
        Utils::filePutsContent
        (
            $this->output_dir . "/$properties_class_name.php",
            $file_content
        );
    }
}