<?php 
	session_start();

	include '/layers/presentation/presentation.php';
	include 'layers/login/logs.php';

	$log = check_login();

	html_head('Manager - Πίνακας Ελέγχου');
	//header("location:waiters.php?action=0");
	//echo '<a  href="categories.php?action=0">';

	$header = "Πίνακας Ελέγχου - Προιόντα";
	$id = "products" ;
	get_header($log, $header);
	get_bar($log, $id);

	if(isset($_GET['page'])) {
		if($_GET['page'] == 'product_list') {
			get_product_control_pannel();
		}
		if($_GET['page'] == 'feature_list') get_features_control_pannel();
	}
	close_page();

?>