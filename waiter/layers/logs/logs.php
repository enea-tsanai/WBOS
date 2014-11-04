<?php

function check_login()
{
	if(isset($_SESSION['waiter']['uid']))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function logout() {
	$waiter["uid"] = $_SESSION['waiter']['uid'];
	$waiter["state"] = "inactive";
	mysql_update_waiter_state( $waiter );

	//session_unset($_SESSION['waiter']['uid']);
	session_destroy();
}
?>