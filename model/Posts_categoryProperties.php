<?php 
namespace CloudsDotEarth\App\Model;
class Posts_categoryProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'posts_category';
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
    * @col post
    * @mysql_type bigint
    * @var string
    */
    public $post;

    /**
    * @col category
    * @mysql_type bigint
    * @var string
    */
    public $category;

    /**
    * @col author
    * @mysql_type bigint
    * @var string
    */
    public $author = null;
}