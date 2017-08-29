<?php
namespace App\Controllers;

use Harps\Controller\View;
use Harps\Utils\Database;

class UserController
{
    public function user_profil($request, $i) {
        $db = new Database();
        $conn = $db->getConnection();
        $users = $db->Send_Request($conn, "SELECT * FROM users WHERE username=?", true, $request);
        $model = $users;

        return View::Load("/Users/board", $model);
    }
}