<?php
namespace App\Controllers;

use Libs\Controller\View;
use App\Managers\HomeManager;
use App\Models\HomeModel;
use Libs\Utils\Database;

class HomeController
{
    public static function index() {
        $db = new Database();
        $conn = $db->getConnection();
        $users = $db->Send_Request($conn, "SELECT * FROM users WHERE username='QuantumSheep'", false);
        $model = new HomeModel();
        $model->a = "Hello";

        HomeManager::getMenu();

        return View::Load("/index.php", $model);
    }
}

?>