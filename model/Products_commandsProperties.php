<?php 
namespace CloudsDotEarth\App\Model;
class Products_commandsProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'products_commands';
    /**
    * @col row_id
    * @mysql_type bigint
    * @var string
    */
    public $row_id;

    /**
    * @col updated_at
    * @mysql_type date
    * @var DateTime
    */
    public $updated_at = null;

    /**
    * @col inserted_at
    * @mysql_type date
    * @var DateTime
    */
    public $inserted_at = null;

    /**
    * @col command
    * @mysql_type bigint
    * @var string
    */
    public $command;

    /**
    * @col product
    * @mysql_type bigint
    * @var string
    */
    public $product;

    /**
    * @col quantity
    * @mysql_type smallint
    * @var int
    */
    public $quantity;

    /**
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;
}