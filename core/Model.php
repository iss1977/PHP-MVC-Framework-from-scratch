<?php


namespace app\core;


abstract class Model // abstract just to not create mistakenly an object from it.
{
    public const RULE_REQUIRED ='required';
    public const RULE_EMAIL ='email';
    public const RULE_MIN ='min';
    public const RULE_MAX ='max';
    public const RULE_MATCH ='match';
    public const RULE_UNIQUE ='unique';

    public array $errors =[]; // this will contain all the errors as we run the function validate()

    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->{$key} = $value;
            }
        }
    }

    abstract function rules():array; // this must be implemented

    /** This are the labels for the form model. Will be overwritten in child (ex User and LoginForm) */
    public function labels():array
    {
        return [];
    }

    public function getLabel($attribute)
    {
        return $this->labels()[$attribute]??$attribute;
    }

    /**
     * Validates the form
    */
    public function validate(): bool
    {
        foreach($this->rules() as $attribute => $rules){ // $rules will be an array. See child class. Ex: class Users:  'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'class'=> self::class]]
            $value = $this->{$attribute} ?? false; // the data stored with loadData. $value contains the sent form data. if the object don't have that attribute, $value will be false
            foreach ($rules as $rule) {
                $ruleName = $rule; // if it's an array it will be overwritten
                if (is_array($ruleName)){
                    $ruleName = $rule[0]; // we take the $ruleName from the first element
                }
                // now we have a $ruleName representing the name of th rule. ex: self::RULE_MIN from  " [self::RULE_MIN, 'min' => 8] "
                if ($ruleName===self::RULE_REQUIRED && !$value){ // if this rule exist and the value is not set, rule not applied.
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value,FILTER_VALIDATE_EMAIL)){
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && !($rule['min'] <= strlen($value)) ){
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && ($rule['max'] < strlen($value)) ){
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $this->{$rule['match']} !==$value){
                    $rule['match'] = $this->getLabel($rule['match']); // to change the text of the error message from 'password' to the label : Password
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE){
                    $className = $rule['class']; // in this class is the name of the table where the field should be unique.
                    $uniqueFieldName = $rule['unique-field'] ?? $attribute;
                    $tableName = $className::tableName();
                    $sql= "SELECT * FROM $tableName where $uniqueFieldName = :param"; // SELECT * FROM users WHERE email = :email
                    $statement = Application::$app->db->prepare($sql);
                    $statement->bindValue(":param",$value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record){
                       $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field'=>$this->getLabel($attribute)]);
                    }

                }
            } //foreach
        } //foreach

        return empty($this->errors); // if there are no errors, we return true.
    }

    /** Adds a new validation error when validating data.
     * @param string $attribute
     * @param string $rule
     * @param array $params
     */
    private function addErrorForRule(string $attribute, string $rule, $params = []) // $params is the $rule . ex: [self::RULE_MIN, 'min' => 8]
    {
        $message = $this->errorMessages()[$rule] ?? '-error-';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}",$value,$message);
        }

        $this->errors[$attribute][]= $message; // this way we add an element to the array.

    }

    public function addError(string $attribute, string $message) // $params is the $rule . ex: [self::RULE_MIN, 'min' => 8]
    {
        $this->errors[$attribute][]= $message; // this way we add an element to the array.
    }


    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'Must be a valid e-mail address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must match with {match}',
            self::RULE_UNIQUE => 'Record with this {field} already exists.'
        ];
    }

    public function hasErrors($attribute)
    {
        return ($this->errors[$attribute]?? false);

    }

    // we use this in the view
    public function getFirstError(string $attribute){
        return $this->errors[$attribute][0] ?? 'No errors';
    }

}