<?php
namespace Controller;

class EntityModel
{
    public function Load($model) {
        require(DIR_APP . "/EntityModel/Row" . $model . ".php");
    }
}