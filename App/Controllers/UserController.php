<?php
namespace App\Controllers;

use Harps\Controllers\Controller;
use Harps\Utils\Database;

class UserController extends Controller
{
    public function user_profil($request) {
        $db = new Database();
        $conn = $db->getConnection();
        $users = $db->Send_Request($conn, "SELECT * FROM users WHERE username=?", true, $request);
        $model = $users;

        return self::view("/Users/board", $model);
    }
}