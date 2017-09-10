<?php
use Harps\Core\Security;

function crsf_gen() {
    return Security::csrf_gen();
}