<?php
namespace Harps\Routing;

use Harps\Client\Session;
use Harps\Routing\Route;

class PathFinder {
    public $registred_path;

    public function __construct(bool $reinit = false) {
        if($reinit == false) {
            if(($path_finder = Session::get("PathFinder")) != null) {
                $this->registred_path = $path_finder;
            } else {
                $this->reinit();
            }
        } else {
            $this->reinit();
        }

        return $this;
    }

    private function update() {
        $this->registred_path = Session::get("PathFinder");
    }

    private function reinit() {
        Session::set(["PathFinder" => array()]);
    }

    public function register_current() {
        $current_path = array(
            "route" => Route::get_current_uri(),
            "post_data" => $_POST,
            "get_data" => $_GET
        );

        $requested_path = Session::get("PathFinder");

        if(end($requested_path) != $current_path) {
            Session::push(["PathFinder" => $current_path]);
        }

        $this->update();

        return $this;
    }

    public function delete_last() {
        Session::set(["PathFinder" => array_pop(Session::get("PathFinder"))]);

        $this->update();

        return $this;
    }
}