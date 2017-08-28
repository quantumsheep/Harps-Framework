<?php
namespace App\Managers;

use Libs\Utils\Database;

class HomeManager
{
    public static function getMenu() {
        $mysqli = Database::getConnection();
    }
}