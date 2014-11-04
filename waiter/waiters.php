<?php 
	session_start();

	include '/layers/presentation/presentation.php';
	include 'layers/login/logs.php';

?>
<!--
-->
<html>

		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>    

   	    <script src="/web_based_ordering_system/project/java/jquery.js" type="text/javascript" ></script>

		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />

</html>
<!-- -->

<?php
	$log = check_login();

	html_head('Manager - Πίνακας Ελέγχου');
	//header("location:waiters.php?action=0");
	//echo '<a  href="waiters.php?action=0">';

	$header = "Πίνακας Ελέγχου - Σερβιτόροι";
	$id = "waiters" ;
	get_header($log, $header);
	get_bar($log, $id);

	//if($_GET['action']=='insert_new_waiter_submit')
	//{
	if (isset($_POST['name'],$_POST['username'])) {
		//print_r($_POST);
		echo 'Your name is '.$_POST['name'];
		insert_new_waiter($_POST, $_FILES, $_SESSION);
	}
	//}
	close_page();
?>