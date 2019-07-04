<?php 
namespace CloudsDotEarth\App\Model;
class ProductsProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'products';
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
    * @col title
    * @mysql_type character varying
    * @var string
    */
    public $title;

    /**
    * @col description
    * @mysql_type character varying
    * @var string
    */
    public $description;

    /**
    * @col price
    * @mysql_type numeric
    * @var float
    */
    public $price;

    /**
    * @col price_per_kilo
    * @mysql_type boolean
    * @var bool
    */
    public $price_per_kilo = null;

    /**
    * @col picture
    * @mysql_type character varying
    * @var string
    */
    public $picture;

    /**
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;
}