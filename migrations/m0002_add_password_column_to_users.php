<?php

class m0002_add_password_column_to_users
{
    public function up()
    {
        $SQL = "ALTER TABLE users ADD COLUMN password VARCHAR(255) NOT NULL ";
        $db= \app\core\Application::$app->db;
        $db->pdo->exec($SQL);
    }

    public function down()
    {
        $SQL = "ALTER TABLE users DROP COLUMN password";
        $db= \app\core\Application::$app->db;
        $db->pdo->exec($SQL);
    }
}