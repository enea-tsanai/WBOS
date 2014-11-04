<?php

include_once '../../../layers/logic/mysql.php';
include 'logs.php';

//$log = check_login();
//$error = '';
session_start();

	if(!empty($_GET['action'])) {

		if($_GET["action"] == "login") {
			if( !empty($_POST['username']) && !empty($_POST['password']) ) {
				$response = mysql_validate_waiter_login($_POST['username'], $_POST['password'] );
				
				if($response['error'] == '')
				{
					//session_register($response['username']);
					$_SESSION['waiter']['uid'] = $response['uid'];
                	$_SESSION['waiter']['username'] = $response['username'];
					//echo session_id(); 
					$jResult['Result'] = "OK";
				}
				else $jResult['Result'] = "ERROR";
			} else $jResult['Result'] = "ERROR";

			print json_encode($jResult);
		}

		if($_GET["action"] == "logout") {

			$log = check_login();
			if($log == false) {
				$jResult['Result'] = "Error";
			}
			else {
				$jResult['username'] = $_SESSION['waiter']['username'];
				$jResult['uid'] = $_SESSION['waiter']['uid'];
				logout();
				$jResult['Result'] = "OK";
			}
			print json_encode($jResult);
		}
		
		if($_GET["action"] == "get_session") {
			$log = check_login();
			if($log == false) {
				$jResult['Result']="No_Session";
			}
			else {
				$jResult['username'] = $_SESSION['waiter']['username'];
				$jResult['uid'] = $_SESSION['waiter']['uid'];
				$jResult['Result']="OK";
			}
			print json_encode($jResult);
			//echo get_session();
		}
	}

	function get_session() {
		$waiter = check_login();
		if($waiter == false) {
			return "no_session";
		}
		else {
			return $_SESSION['waiter']['username'];
		}
	}

?>