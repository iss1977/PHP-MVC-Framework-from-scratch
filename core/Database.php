<?php


namespace app\core;


class Database
{

    public \PDO $pdo;

    /**
     * Database constructor. Setting up the connection to the database
     * @param array $config
     */
    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // if there is a connection problem, it will throw an error and will not pass unobserved.
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();//create if exist
        $alreadyAppliedMigrations = $this->getAppliedMigrations();

        $newMigrations =[];
        $files = scandir(Application::$ROOT_DIR.'/migrations');

        $toBeAppliedMigrations = array_diff($files,$alreadyAppliedMigrations); // only the not applied migrations will run.

        foreach ($toBeAppliedMigrations as $migration){
            if ($migration === '.' || $migration ==='..'){
                continue;
            }
            require_once Application::$ROOT_DIR.'/migrations/'.$migration;
            $classname = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $classname();
            $this->log("Applying migration $migration ...");
            $instance->up();
            $this->log("... done.".PHP_EOL);
            $newMigrations[]=$migration;
        }
        if (!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations already applied");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS `mvc_framework`.`migrations` (
            `id` INT NOT NULL AUTO_INCREMENT,
            `migration` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `migrationscol` VARCHAR(45) NULL,
            PRIMARY KEY (`id`));

        ");
    }

    public function getAppliedMigrations(): array
    {
        $statement =  $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    private function saveMigrations(array $newMigrations)
    {
        $str=array_map(fn($m)=>"('$m')", $newMigrations); // this transforms the filename from m0001_initial.php to ('m0001_initial.php') to be usable in the query statement
        $str = implode(',',$str); // to concatenate the array elements delimited with commas

        $statement = $this->pdo->prepare("INSERT INTO migrations(migration) VALUES $str");
        $statement->execute();
    }
    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }

    /** Helper method
     * @param $stm
     * @return false|\PDOStatement
     */
    public static function prepare($stm) // shortcut to Application::$app->db->pdo->prepare(). For easy access.
    {
        return Application::$app->db->pdo->prepare($stm); // returns a statement.
    }


}