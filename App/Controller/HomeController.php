<?php
use Controller\View;
use Controller\Model;
use Controller\Manager;

Model::Load("Home");
Manager::Load("Home");

class HomeController
{
    public function index() {
        $model = new HomeModel();
        $model->a = "Hello";

        HomeManager::getMenu();

        return View::Load("/index.php", $model);
    }
}