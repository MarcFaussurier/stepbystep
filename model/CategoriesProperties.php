<?php 
namespace CloudsDotEarth\App\Model;
class CategoriesProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'categories';
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
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;

    /**
    * @col parent
    * @mysql_type bigint
    * @var string
    */
    public $parent;
}