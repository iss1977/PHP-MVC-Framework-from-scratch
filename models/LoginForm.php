<?php


namespace app\models;


use app\core\Application;
use app\core\Model;

class LoginForm extends Model // Not DBModel, because it's not mapping anything to the database.
{

    // data used in the form
    public string $email='';
    public string $password='';
    //-----------------------




    function rules(): array
    {
        return [
            'email'=> [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED]
        ];
    }

    /** Will check if the user exists in the DB, check the password and if OK login the user. */
    public function login(): bool
    {
        $user = User::findOne(['email'=>$this->email]);

        if(!$user){
            $this->addError('email','User does not exist.');
            return false;
        }
        if (!password_verify($this->password, $user->password)){
            $this->addError('password','Password is incorrect');
            return false;
        }
        return Application::$app->login($user); // if we arrive here, the user can be logged in.
    }

    /** Overwrite the labels from parent class */
    public function labels():array
    {
        return [
            'email'=>'Your Email',
            'password'=> 'Password'
        ];
    }

}