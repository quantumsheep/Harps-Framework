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
        $model = new HomeModel();
        $model->a = "Hello";

        return View::Load("index", $model);
    }

    public static function AjaxTest() {
        echo 'hi';
    }
}

?>