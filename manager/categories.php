<?php 
	session_start();

	include '/layers/presentation/presentation.php';
	include 'layers/login/logs.php';

	$log = check_login();

	html_head('Manager - Πίνακας Ελέγχου');
	//header("location:waiters.php?action=0");
	//echo '<a  href="categories.php?action=0">';

	$header = "Πίνακας Ελέγχου - Κατηγορίες";
	$id = "categories" ;
	get_header($log, $header);
	get_bar($log, $id);
	close_page();
?>