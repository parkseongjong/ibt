<?php
@set_time_limit(78600);

if (isset($_SERVER['argv'][1])) {
    $start = $_SERVER['argv'][1];
} else {
    $start = date('Y-m-d');
}

if (isset($_SERVER['argv'][2])) {
    $end = $_SERVER['argv'][2];
} else {
    $end = date('Y-m-d');
}
$new_date = date("Y-m-d", strtotime("-10 days", strtotime($start)));
$new_date = '2021-01-10';

while(true) {
    $new_date = date("Y-m-d", strtotime("+1 day", strtotime($new_date)));
    echo exec("php ./stats_transactions.php ". $new_date). PHP_EOL;
#    sleep(1);
    if($new_date == $end) {
        break;
    }
}