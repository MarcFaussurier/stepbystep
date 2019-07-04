<?php 
namespace CloudsDotEarth\App\Model;
class ContactsProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'contacts';
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
    * @col email
    * @mysql_type character varying
    * @var string
    */
    public $email;
}