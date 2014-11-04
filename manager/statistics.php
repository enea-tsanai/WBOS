<?php 
	session_start();

	include '/layers/presentation/presentation.php';
	include 'layers/login/logs.php';

	$log = check_login();

	html_head('Manager - Πίνακας Ελέγχου');
	//header("location:waiters.php?action=0");
	//echo '<a  href="categories.php?action=0">';

	$header = "Πίνακας Ελέγχου - Στατιστικά";
	$id = "Statistics" ;
	get_header($log, $header);
	get_bar($log, $id);

	if(isset($_GET['page'])) {
		if($_GET['page'] == 'incomes') get_statistics_incomes_control_pannel();
		if($_GET['page'] == 'products') get_statistics_products_control_pannel();
	}

	close_page();

?>