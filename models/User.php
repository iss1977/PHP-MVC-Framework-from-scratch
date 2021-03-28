<?php


namespace app\models;


use app\core\DbModel;
use app\core\Model;

class User extends DbModel
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;



    public string $firstname='';
    public string $lastname='';
    public string $email='';
    public int $status = self::STATUS_INACTIVE;
    public string $password='';
    public string $confirmPassword='';


    /**
     * this will be called from AuthController like this:  "$user->save();"
     *
     */
    public function save():bool
    {
        $this->status= self::STATUS_DELETED;
        $this->password= (string) password_hash($this->password,PASSWORD_DEFAULT); // it's working also without casting
        return parent::save();
    }

    function rules(): array
    {
        // TODO: Implement rules() method.
        return [
            'firstname' => [self::RULE_REQUIRED],
            'lastname' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class'=> self::class, 'unique-field'=>'email']], // we specify where the email should be unique. tablename is an alternative, but we can take out the tablename from the class :"self::class"
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8],[self::RULE_MAX, 'max' => 24]],
            'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH,'match'=>'password']]
        ];
    }

    /** Returns the name of the table associated with this class*/
    public function tableName(): string
    {
        return 'users';
    }

    /** Returns a list of fields to be saved in the database*/
    public function attributes(): array
    {
        return ['firstname', 'lastname','email','password','status'];
    }

    /** Setup an array with the labels in the form*/
    public function labels():array
    {
        return [
            'firstname' => 'First name',
            'lastname' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'confirmPassword' => 'Confirm password'
        ];
    }

}