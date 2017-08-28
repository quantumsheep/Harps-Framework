<?php
namespace App\Controllers;

use Libs\Controller\View;
use Libs\Controller\Model;
use Libs\Controller\Manager;
use Libs\Utils\Database;
use Libs\Utils\Tools;
use App\Managers\UsersManager;

class UserController
{
    public function user_profil($request, $i) {
        $conn = Database::getConnection();

        $model = new UsersModel();

        $model->rowuser = UsersManager::getUser($conn, $request["profilId"]);
        $model->request = $request;

        return View::Load("/Users/board.php", $model);
    }
}