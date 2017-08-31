<?php
namespace App\Controllers;

use Harps\Controllers\Controller;
use App\Models\HomeModel;

class HomeController extends Controller
{
    public static function index() {
        $model = new HomeModel();
        $model->a = "Hello";

        return self::view("index", $model);
    }

    public static function AjaxTest() {
        echo 'hi';
    }
}