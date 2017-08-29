<?php
namespace App\Controllers;

use Harps\Controller\View;
use App\Managers\HomeManager;
use App\Models\HomeModel;
use Harps\Utils\Database;
use Harps\Core\Controller;
use Harps\Utils\SMTP;

class HomeController extends Controller
{
    public static function index() {
        $smtp = new SMTP();
        $model = new HomeModel();
        $model->a = "Hello";

        HomeManager::getMenu();

        return View::Load("index", $model);
    }
}

?>