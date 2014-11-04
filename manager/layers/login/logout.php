<?php

session_start();

include '../presentation/presentation.php';
include 'logs.php';

$log = check_login();

html_head('Manager - Logout');

if($log == false)
{
	echo '<script type="text/javascript">login_fail("LOGGED ON");</script>';
}
else
{
	logout();
	header("location:../../manager_homepage.php");
}
?>