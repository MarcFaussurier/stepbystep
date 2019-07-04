<?php 
namespace CloudsDotEarth\App\Model;
class CommandsProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'commands';
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
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;

    /**
    * @col category
    * @mysql_type bigint
    * @var string
    */
    public $category;
}