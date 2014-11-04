<?php

session_start();

include 'layers/presentation/presentation.php';
include 'layers/login/logs.php';
include_once 'layers/logic/mysql.php';
		
?>

<!--<html>
	<link rel="stylesheet" href="/web_based_ordering_system/project/css/manager_login.css" type="text/css" />
</html>
-->
<?php

	$log = check_login();
	$error = '';

	html_head('Manager - Home');

	if( (!empty($_POST['username'])) && (!empty($_POST['password'])) ) {

		$response = mysql_validate_login($_POST["username"], $_POST["password"]);
		//echo session_id();
		//print_r($error);
		//var_dump($_POST);
		if($response['error'] == '') {
			$_SESSION['manager']['uid'] = $response["uid"];
			$_SESSION['manager']['username'] = $response["username"];
			header("location:manager_homepage.php");
		} else {
			?><script>alert('Λάθος username ή κωδικός!')</script><?php
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}
	} else if( (empty($_POST['username'])) && (!empty($_POST['password'])) ) {
			?><script>alert('Συμπληρώστε το Username')</script><?php
	} else if( (!empty($_POST['username'])) && (empty($_POST['password'])) ) {
			?><script>alert('Συμπληρώστε το κωδικό')</script><?php
	} else {
		?><script>alert('Συμπληρώστε και τα 2 πεδία')</script><?php
	}	

	//var_dump($log);
	//initialize();
	$header = "Διαχειριστής-Πίνακας Ελέγχου";
	$id = "login";
	get_header($log, $header);
	get_bar($log,$id);
	get_login_table();
	close_page();// username and password sent from form 

?>