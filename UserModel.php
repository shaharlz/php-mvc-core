<?php


namespace App\Core;


abstract class UserModel extends DatabaseModel {

    public abstract function getDisplayName(): string;

}