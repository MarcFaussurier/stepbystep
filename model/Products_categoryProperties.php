<?php 
namespace CloudsDotEarth\App\Model;
class Products_categoryProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'products_category';
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
    * @col category
    * @mysql_type bigint
    * @var string
    */
    public $category = null;

    /**
    * @col product
    * @mysql_type bigint
    * @var string
    */
    public $product = null;

    /**
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;
}