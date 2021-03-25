<?php

class m0001_initial
{
    public function up()
    {
        $SQL = "
            CREATE TABLE IF NOT EXISTS `mvc_framework`.`users` (
              `id` INT NOT NULL AUTO_INCREMENT,
              `email` VARCHAR(255) NOT NULL,
              `firstname` VARCHAR(255) NOT NULL,
              `lastname` VARCHAR(255) NOT NULL,
              `status` TINYINT NOT NULL,
              `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`)
            );

        ";
        $db= \app\core\Application::$app->db;
        $db->pdo->exec($SQL);
    }

    public function down()
    {
        $SQL = "DROP TABLE `mvc_framework`.`users` ";
        $db= \app\core\Application::$app->db;
        $db->pdo->exec($SQL);
    }
}