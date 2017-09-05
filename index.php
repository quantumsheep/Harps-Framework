<?php
$time = microtime(TRUE);
$mem = memory_get_usage();

require_once(__DIR__ . "/Harps/Boot.php");
Boot::Harps();

$loading_data = array("seconds" => microtime(TRUE) - $time, "memory" => (memory_get_usage() - $mem) / (1024));

if(DEV == true) { ?>

<div style="position:fixed;bottom:0;width:100%;background:rgba(0, 0, 0, 0.40);color:#fff;display:flex;">
    <div style="padding:5px;">
        <span>Memory usage: </span>
        <span>
            <?php echo $loading_data["memory"]; ?> Ko
        </span>
    </div>
    <span style="padding:5px;">| </span>
    <div style="padding:5px;">
        <span>Loading time: </span>
        <span>
            <?php echo $loading_data["seconds"]; ?> seconds
        </span>
    </div>
</div>
<?php } ?>

<?php