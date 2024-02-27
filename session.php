<?php
header('Content-Type: text/plain');
session_start();
if(!isset($_SESSION['visit']))
{
echo "Memcache Failover Testing | E2E Networks Private Limited\n";
$_SESSION['visit'] = 0;
}
else
echo "You have visited this server ".$_SESSION['visit'] . " times. \n";
$_SESSION['visit']++;
echo "Server IP: ".$_SERVER['SERVER_ADDR'] . "\n";
echo "Client IP: ".$_SERVER['REMOTE_ADDR'] . "\n";
print_r($_COOKIE);
?>
