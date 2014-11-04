<?php

	include_once "C:/xampp/htdocs/web_based_ordering_system/project/layers/logic/mysql.php";
	include 'C:/xampp/htdocs/web_based_ordering_system/project/layers/logic/logic.php';

	function html_head($title) {
		
		?>
		<!DOCTYPE HTML>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<?php echo '<title>'.$title.'</title>'; ?>

		<script src="http://www.jtable.org//Scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
		<script src="http://www.jtable.org//Scripts/jquery-ui-1.10.0.min.js" type="text/javascript"></script>
		 <link href="http://www.jtable.org//Content/themes/metroblue/jquery-ui.css" rel="stylesheet" type="text/css" />
		 <link href="http://www.jtable.org//Scripts/jtable/themes/metro/blue/jtable.css" rel="stylesheet" type="text/css" />
		<script src="/web_based_ordering_system/project/jtable.2.3.0/jquery.jtable.js" type="text/javascript"></script>

		<!--
		<link href="/web_based_ordering_system/project/jtable.2.3.0/themes/metro/blue/jtable.css" rel="stylesheet" type="text/css" />
	    <script src="/web_based_ordering_system/project/jtable.2.3.0/jquery.jtable.js" type="text/javascript"></script>
		<script src="http://www.jtable.org//Scripts/jtable/jquery.jtable.js" type="text/javascript"></script>
		-->

		<!--Control Panel javascript -->
		<script src="/web_based_ordering_system/project/java/jqueryCentralOrders.js" type="text/javascript"></script>
		<script src="/web_based_ordering_system/project/java/jqueryOrders.js" type="text/javascript"></script>
		<script src="/web_based_ordering_system/project/java/jqueryProducts.js" type="text/javascript"></script>
		<script src="/web_based_ordering_system/project/java/jqueryCategories.js" type="text/javascript"></script>
		<script src="/web_based_ordering_system/project/java/jqueryFeatures.js" type="text/javascript"></script>
		<script src="/web_based_ordering_system/project/java/jqueryStatistics.js" type="text/javascript"></script>

 		<!--Waiters 
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />-->
		
		<!--Manager Login -->
		<link rel="stylesheet" href="/web_based_ordering_system/project/css/manager_login.css" type="text/css" />
		
		<!--Presentation -->
		<link rel="stylesheet" href="/web_based_ordering_system/project/css/presentation.css" type="text/css" />
		
		<!--Presentation -->
		<link rel="stylesheet" href="/web_based_ordering_system/project/css/control_panel.css" type="text/css" />

		</head>

		<?php
	}

	function get_header($log, $header) {
		
		if($log)
		{
			?>
            <div id="header" style="background-color:#4169E1; width:100%; height:120px;">
				<a href = "manager_homepage.php"><img src="images/logo.jpg" height="120px" width="200px" /></a>
				<a ><img src="images/CAPTURE.png" height="120px" width="73%" /></a>
			<?php //echo '<h3 style="margin-bottom:0; color:#FFF;">'.$header.'</h3></div>' ;
		}
		else {
			?>
				<div id="header" style="background-color:#4169E1; width:100%; height:120px;">
				<a href = "manager_homepage.php"><img src="images/logo.jpg" height="120px" width="200px" /></a>
				<a ><img src="images/CAPTURE.png" height="120px" width="73%" /></a>
			<?php
		}
		?></div><?php
	}

	function get_bar($log, $id)	{
		?>
			<a id="home" style="font-size:14px" href="manager_homepage.php">Αρχική</a>
		<?php
		
		if($log)
		{	
			echo '<a id="logout" style="font-size:14px" href = "layers/login/logout.php">Έξοδος</a>';
			$manager_info=mysql_get_manager_info(1);	//auto prepei na allaksei!
			echo '<div id="" style="font-size:14px"> Διαχειριστής: '.$manager_info['username'].'</div>';
			 
			if ($id == "manager_homepage") {
				get_control_pannel();
			}
			if ($id == "waiters") {
				get_waiter_control_pannel();
			}
			if ($id == "orders") {
				get_order_control_pannel();
			}
			if ($id == "categories") {
				get_category_control_pannel();
			}
			if ($id == "products") {
				?>
				<div id="manage_products">
					<div class="manage_products_options">
						<div class="expand-up">
							<ul>
							  <li>
							    <a href="products.php?page=product_list">
							      <span>Προϊόντα</span>
							      <img src="images/classiccoctails.jpg" />
							    </a>
							  </li>
							  <li>
							    <a href="products.php?page=feature_list">
							      <span>Επιπρόσθετα υλικά</span>
							      <img src="images/fruit-cocktail.jpg" />
							    </a>
							  </li>
							</ul>
						</div>
					</div>
				</div>
				<?php
				//get_product_control_pannel();
			}

			if ($id == "Statistics") {
				
				?>
				<div id="statistics_manage_products">
					<div class="statistics_manage_products_options">
						<div class="expand-up">
							<ul>
							  <li>
							    <a href="statistics.php?page=incomes">
							      <span>Έσοδα</span>
							      <img src="images/incomes.jpg" />
							    </a>
							  </li>
							  <li>
							    <a href="statistics.php?page=products">
							      <span>Προϊόντα</span>
							      <img src="images/sold_products.png" />
							    </a>
							  </li>
							</ul>
						</div>
					</div>
				</div>
				<?php
			}	
		}
		else
		{
			?>
			<a id="login" style="font-size:14px" href="manager_login.php">Είσοδος</a>
			
			<?php
		}
		?><?php
	}

	function get_control_pannel() {
		
		?>
			<div class="control_panel_ui">
				<div class="control_panel_ui_options">

					<div class="expand-up">
					<ul>
					  <li>
					    <a href="waiters.php">
					      <span>Σερβιτόροι</span>
					      <img src="images/waiter.png" />
					    </a>
					  </li>
					  <li>
					    <a href="orders.php">
					      <span>Παραγγελείες</span>
					      <img src="images/fundraising-order.png" />
					    </a>
					  </li>
					  <li>
					    <a href="products.php">
					      <span>Προϊόντα</span>
					      <img src="images/product.gif" />
					    </a>
					  </li>
					  <li>
					    <a href="categories.php">
					      <span>Κατηγορίες</span>
					      <img src="images/categories.png" />
					    </a>
					  </li>
					  <li>
					    <a href="statistics.php">
					      <span>Στατιστικά</span>
					      <img src="images/statistics.png" />
					    </a>
					  </li>
					</ul>

					</div>
				</div>

				<div id="centralOrdersTable" > </div>
		</div>
		
		  <?php
	}

	function get_waiter_control_pannel() {

		$log = check_login();
		?>
		 <div id="My-div" class="">
		    <div id="dialog-form" title="Create new user" action="layers/logic/mysql.php?action=add_waiter" >
		      <p class="validateTips">Όλα τα πεδία είναι απαραίτητα.</p>
		      <form>
		      <fieldset>
		        <label for="username">Username</label>
		        <input type="text" name="username" id="username" class="text ui-widget-content ui-corner-all" />
		    	<label for="name">Όνομα</label>
		        <input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
		        <label for="lastname">Επώνυμο</label>
		        <input type="text" lastname="lastname" id="lastname" class="text ui-widget-content ui-corner-all" />
		        <label for="phone_number">Αριθμός τηλεφώνου</label>
		        <input type="text" name="phone_number" id="phone_number" class="text ui-widget-content ui-corner-all" />
		        <label for="password">Κωδικός</label>
		        <input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
		        <label for="confirm_password">Επιβεβαίωση κωδικού</label>
		        <input type="password" name="confirm_password" id="confirm_password" value="" class="text ui-widget-content ui-corner-all" />
		      </fieldset>
		      </form>
		    </div>
		    <div id="users-contain" class="ui-widget">
		      <h1>ΠΙΝΑΚΑΣ ΣΕΡΒΙΤΟΡΩΝ</h1>
		      <table id="users" class="ui-widget ui-widget-content">
		        <thead>
		          <tr class="ui-widget-header ">
		            <th>Uid</th>
		            <th>Username</th>
		            <th>Όνομα</th>
		            <th>Επώνυμο</th>
		            <th>Αρ.τηλεφώνου</th>
		            <th>Κωδικός</th>
		            <th>Φωτογραφία</th>
		            <th>Κατάσταση</th>
		            <th>Actions</th>
		          </tr>
		        </thead>
		        <tbody>
		          <tr>
		          </tr>
		        </tbody>
		      </table>
		    </div>
		    <button id="create-user">Προσθήκη εγγραφής</button>
			
			<?php
				$waiters = mysql_get_waiters_list();
			 ?>
			<form class="upload_photo" action="layers/logic/logic.php?action=upload_image" method="post" enctype="multipart/form-data">
				<h1>Προσθήκη φωτογραφιών</h1>
				<label for="waiter_uid">waiter username</label>
				<select name="waiter_uid" class="selectwaiter">
					<?php
						foreach ($waiters as &$waiter) {
							echo '<option value="'.$waiter['uid'].'">'.$waiter['username'].'</option>';
						}
					?>
				</select>
				<label for="file">Φωτογραφία:</label>
				<input type="file" name="file" id="file"><br>
				<input type="submit" name="submit" value="Υποβολή">
			</form>

		</div>
		<?php
	}

	function get_category_control_pannel() {
		$log = check_login();
		?><div id="CategoryTable"></div><?php
	}

	function get_order_control_pannel() {

		$log = check_login();
		?><div id="OrdersTable"></div><?php
	}

	function get_product_control_pannel() {

		$log = check_login();
		?><div id="ProductTable"></div><?php
	}

	function get_features_control_pannel() {

		$log = check_login();
		?><div id="FeaturesTable"></div><?php
	}

	function get_statistics_incomes_control_pannel() {

		$log = check_login();
		?>
		<div id="StatisticsPageIncomes">
		 	<form class="StatisticsPageIncomesRadio">
		  		<div id="IncomesRadio">
		  		<p>Εμφάνιση τζίρων ανα:</p>
		    	<input type="radio" id="radio1" name="radio" value="1" checked="checked" /><label for="radio1">Ημέρα</label>
				<input type="radio" id="radio2" name="radio" value="2" /><label for="radio2">Μήνα</label>
				<input type="radio" id="radio3" name="radio" value="3" /><label for="radio3">Χρόνο</label>
		  		</div>
			</form>
		</div><?php
	}	

	function get_statistics_products_control_pannel() {
	
		$log = check_login();
		?>
		<div id="StatisticsPageProducts">
		 	<form class="StatisticsPageProductsRadio">
		  		<div id="ProductsRadio"><p>
		    	Αριθμός προϊόντων για εμφάνιση: </p><input type="text" name="numberofproducts" value="5"><br>
		    	<p>Εμφάνιση προϊόντων με:</p>
		    	<input type="radio" id="radio1" name="radio" value="1" checked="checked" /><label for="radio1">Περισσότερες πωλήσεις</label>
				<input type="radio" id="radio2" name="radio" value="2" /><label for="radio2">Λιγότερες πωλήσεις</label>
		  		</div>
			</form>
		
		</div><?php
		//<a><img src="images/login.png" height="500px" width="73%" /></a>
	}

	function get_login_table() {
		
		?>
		<div class="login">
		    <h1>Είσοδος Διαχειριστή</h1>
		    <form method="post" action="manager_login.php?attemp=1">
		      <p><input type="text" name="username" value="" placeholder="Ψευδώνυμο"></p>
		      <p><input type="password" name="password" value="" placeholder="Κωδικός"></p>
		      <p class="submit"><input type="submit" name="Submit" value="Είσοδος"></p>
		    </form>
		</div>
		<?php
	}

	function close_page() {

		?><div id="footer"></div>
		</body>
		</html>
        <?php
	}

?>