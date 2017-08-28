<?php
use Controller\View;
use Controller\Model;
use Controller\Manager;
use Controller\EntityModel;
use Utils\Database;
use Utils\Tools;

Model::Load("Users");
Manager::Load("Users");

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