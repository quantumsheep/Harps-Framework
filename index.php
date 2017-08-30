<?php
$time = microtime(TRUE);
$mem = memory_get_usage();

require_once(__DIR__ . "/Harps/Boot.php");
Boot::Harps();

print_r(array(
  'memory' => (memory_get_usage() - $mem) / (1024 * 1024),
  'seconds' => microtime(TRUE) - $time
));