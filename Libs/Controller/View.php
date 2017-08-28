<?php
namespace Libs\Controller;

class View {
    public static function Load($view, $var) {
        return [$view, $var];
    }
}