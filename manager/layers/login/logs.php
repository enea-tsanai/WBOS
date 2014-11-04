<?php

function check_login()
{
	if(isset($_SESSION['manager']['uid']))
	//if(isset($_SESSION['uid']))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function logout()
{
	//session_unset($_SESSION['manager']['uid']);
	//session_destroy($_SESSION['manager']['uid']);
	//session_unset();
	session_destroy();
}
?>