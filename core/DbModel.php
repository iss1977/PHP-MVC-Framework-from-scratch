<?php


namespace app\core;

// this wil map the user class to the database table. Base active record class.
abstract class DbModel extends Model
{
    abstract public function tableName(): string; // all implementing classes must have a corresponding sql table name. class "User" will have "users"
    abstract public function attributes(): array; // list of tusehe fields that have to be saved into the database.
    abstract public function primaryKey(): string; // returns the name of the primary key ex: 'id'

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

    /** We use this method to check if email exists when trying to login
     * @param array $where
     * @return mixed
     */
    public static function findOne(array $where) // $where looks like ['email'=> 'zura@example.com', 'firstname'=>'Thomas Cook']
    {
        $tableName = static::tableName(); // DbModel has this static attribute
        // we need to write this:
        // SELECT * FROM $tablename WHERE email = :email AND firstname=:firstname
        $attributes = array_keys($where);
        $where_clause_sql= implode('AND', array_map(fn($attr)=>":$attr=$attr",$attributes));
        $sql = "SELECT * FROM $tableName WHERE $where_clause_sql ;";
        $statement = self::prepare($sql);
        foreach ($where as $key => $value) {
            $statement->bindValue(":$key", $value);
        }
        $statement->execute();
        return $statement->fetchObject(static::class); // this will create an object of type static::class (ex: User) containing the fetched data.
    }



}