<?php


namespace app\core;


abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string; // to be implemented in child class
}