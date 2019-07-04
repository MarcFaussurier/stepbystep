<?php 
namespace CloudsDotEarth\App\Model;
class UsersProperties extends \CloudsDotEarth\StepByStep\Model
{
    public $table_name = 'users';
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
    * @col first_name
    * @mysql_type character varying
    * @var string
    */
    public $first_name;

    /**
    * @col last_name
    * @mysql_type character varying
    * @var string
    */
    public $last_name;

    /**
    * @col email
    * @mysql_type character varying
    * @var string
    */
    public $email;

    /**
    * @col password_hash
    * @mysql_type text
    * @var string
    */
    public $password_hash;

    /**
    * @col role
    * @mysql_type integer
    * @var int
    */
    public $role;
}