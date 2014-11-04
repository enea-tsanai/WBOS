<?php 
session_start();

include 'layers/presentation/presentation.php';
include 'layers/login/logs.php';

$log = check_login();

?>

<?php


html_head('Manager - Home');

$header = "Διαχειριστής-Πίνακας Ελέγχου";
$id = "manager_homepage";
get_header($log, $header);
get_bar($log, $id);
//get_mainpage($log);
close_page();

?>