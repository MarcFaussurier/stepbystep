<?php 
namespace CloudsDotEarth\App\Model;
class PostsProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'posts';
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
    * @col content
    * @mysql_type text
    * @var string
    */
    public $content;

    /**
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;
}