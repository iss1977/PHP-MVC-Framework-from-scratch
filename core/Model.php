<?php


namespace app\core;


abstract class Model // abstract just to not create mistakenly an object from it.
{
    public const RULE_REQUIRED ='required';
    public const RULE_EMAIL ='email';
    public const RULE_MIN ='min';
    public const RULE_MAX ='max';
    public const RULE_MATCH ='match';

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

    public function validate()
    {
        foreach($this->rules() as $attribute => $rules){ // $rules will be an array. See child class.
            $value = $this->{$attribute} ?? false; // the data stored with loadData. $value contains the sent form data. if the object don't have that attribute, $value will be false
            foreach ($rules as $rule) {
                $ruleName = $rule; // if it's an array it will be overwritten
                if (is_array($ruleName)){
                    $ruleName = $rule[0]; // we take the $ruleName from the first element
                }
                // now we have a $ruleName representing the name of th rule. ex: self::RULE_MIN from  " [self::RULE_MIN, 'min' => 8] "
                if ($ruleName===self::RULE_REQUIRED && !$value){ // if this rule exist and the value is not set, rule not applied.
                    $this->addError($attribute, $ruleName);
                }
            }

        }
        return empty($this->errors); // if there are no errors, we return true.
    }

    /** Adds a new validation error when validating data.
     * @param string $attribute
     * @param string $rule
     */
    private function addError(string $attribute,string $rule)
    {
        $message = $this->errorMessages()[$rule] ?? '-error-';
        $this->errors[$attribute][]= $message; // this way we add an element to the array.

    }

    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'This field is required',
            self::RULE_EMAIL => 'Must be a valid e-mail address',
            self::RULE_MIN => 'Min length of this field must be {min}',
            self::RULE_MAX => 'Max length of this field must be {max}',
            self::RULE_MATCH => 'This field must match with {match}'
        ];
    }

}