<?php
namespace App\Managers;

use Harps\Utils\Database;

class HomeManager
{
    public static function getMenu() {
        $mysqli = Database::getConnection();
    }
}