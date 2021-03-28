<?php


namespace app\core;

// this wil map the user class to the database table. Base active record class.
abstract class DbModel extends Model
{
    abstract public function tableName(): string; // all implementing classes must have a corresponding sql table name. class "User" will have "users"
    abstract public function attributes(): array; // list of the fields that have to be saved into the database.

    function rules(): array
    {
        // TODO: Implement rules() method.
    }

    /** Picks up tablename and fields(attributes) from child and writes it to the database; */
    //Will be overwritten by child: user->save()
    public function save(): bool
    {
        $tableName = $this->tableName(); // where to be saved
        $attributes = $this->attributes(); //  fields to be saved
        $fieldsSepCommas = implode(',',$attributes); // will result "firstname, lastname"
        $namedParams = implode(',',array_map(fn($el)=>':'.$el,$attributes));// will result ":firstname, :lastname"
        $statement = self::prepare("INSERT INTO $tableName(".$fieldsSepCommas.") VALUES (".$namedParams.")"); // :firstname,etc ... = named parameters; will look like that : "INSERT INTO $tableName(firstname, lastname) VALUES (:firstname, :lastname)")

        foreach ($attributes as $attribute){ // cycle through fields
            $statement->bindValue(":$attribute", $this->{$attribute}); // will look like $statement->bindValue(":firstname", $this->firstname});
        }
        $statement->execute();
        return true;

    }

    public static function prepare($stm) // shortcut to Application::$app->db->pdo->prepare(). For easy access.
    {
        return Application::$app->db->pdo->prepare($stm); // returns a statement.
    }
}