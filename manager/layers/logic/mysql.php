<?php

function connect_to_db() {
	$host="localhost"; 					// Host name 
	$user="root";	 					// Mysql username 
	$password="39,dmc#31"; 						// Mysql password 
	$db="WBOS";	// Database name 
	//$tbl_name="manager"; 				// Table name 
	$con=mysqli_connect($host,$user,$password,$db);
	mysqli_select_db($con,$db);
	mysqli_query($con,"set character_set_client=utf8");
  	mysqli_query($con,"set character_set_connection=utf8"); 
 	mysqli_query($con,"set collation_connection=utf8"); 
 	mysqli_query($con,"set character_set_results=utf8");
	return $con;
}

function mysql_get_manager_info($uid) {
	$con = connect_to_db();
	$query = "SELECT name, username FROM manager WHERE uid=$uid";
	$result = $con->query($query);
	$res = $result->fetch_array();
	$manager_info['username'] = $res['username'];
	$manager_info['name'] = $res['name'];
	$con->close();
	return $manager_info;
}
//---------------------------------------- MYSQL WAITER ----------------------------------------//

function mysql_insert_new_waiter($new_waiter_info) {
	$con = connect_to_db();
	$username = $new_waiter_info['username'];
	$password = $new_waiter_info['password'];
	$name = $new_waiter_info['name'];
	$lastname = $new_waiter_info['lastname'];
	$phone_number = $new_waiter_info['phone_number'];
	$photo = 0;
	$query = "INSERT INTO waiters(uid, username, password, name, lastname, phone_number, photo ) VALUES (NULL,'$username', '$password', '$name', '$lastname', '$phone_number', '$photo')";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_insert_waiter_image($waiter, $photo) {
	$con = connect_to_db();
 	$query = "UPDATE waiters SET photo='$photo' WHERE waiters.uid='$waiter' ";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_get_waiter_info($uid) {
	$con = connect_to_db();
	$query = "SELECT username, password, name, lastname, phone_number, photo FROM waiters WHERE waiters.uid = $uid";	
	$result = $con->query($query);
	$res = $result->fetch_array();
	$waiter_info['username'] = $res['username'];
	$waiter_info['password'] = $res['password'];
	$waiter_info['name'] = $res['name'];
	$waiter_info['lastname'] = $res['lastname'];
	$waiter_info['phone_number'] = $res['phone_number'];
	$waiter_info['photo'] = $res['photo'];
	$waiter_info['state'] = $res['state'];
	$con->close();
	return $waiter_info;
}

function mysql_delete_waiter( $record ) { 
	$con = connect_to_db();
	$query = "DELETE FROM waiters WHERE waiters.uid = '$record[uid]'";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result == 1;
}

function mysql_edit_waiter ( $record ) {
	$con = connect_to_db();
	$query = "UPDATE waiters
	SET username='$record[username]',
		name = '$record[name]',
		lastname = '$record[lastname]',
	 	phone_number= '$record[phone_number]',
	 	password = '$record[password]',
	 	photo = '$record[photo]'
	 WHERE waiters.uid = '$record[uid]'";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
 	return $result >= 0;
}

function mysql_update_waiter_state ( $waiter ) {
	$con = connect_to_db();
	$query = "UPDATE waiters
	SET state='$waiter[state]'
	 WHERE waiters.uid = '$waiter[uid]'";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
 	return $result >= 0;
}

function mysql_check_waiters_record( $record ) {
	$con = connect_to_db();
	$query = "SELECT * FROM waiters WHERE waiters.uid = $record[uid] ";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result == 1;
}


function mysql_get_waiter_image($image_id) {
	$con = connect_to_db();
	$query = "SELECT * FROM photos WHERE pid=$image_id";
	$result = $con->query($query);
	$res = $result->fetch_array();
	$image['title'] = $res['title'];
	$image['path'] = $res['path'];
	$query = "SELECT dateUploaded FROM waiters_photo WHERE pic_id = $image_id";
	$result = $con->query($query);
	$res = $result->fetch_array();
	$image['date'] = $res['dateUploaded'];
	$image['username'] = $res['username'];
	$con->close();	
	return $image;
}

function mysql_get_waiter( $waiter ) {
	$con = connect_to_db();
	$query = "SELECT * FROM waiters WHERE '$waiter' = username";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$uid = $res['uid'];
		$new_waiter[$i]['uid'] = $res['uid'];
		$new_waiter[$i]['username'] = $res['username'];
		$new_waiter[$i]['name'] = $res['name'];
		$new_waiter[$i]['lastname'] = $res['lastname'];
		$new_waiter[$i]['phone_number'] = $res['phone_number'];
		$new_waiter[$i]['password'] = $res['password'];
		$new_waiter[$i]['photo'] = $res['photo'];
		$waiter_info['state'] = $res['state'];
		$i++;
	}
	$con->close();
	if($i>0) return $new_waiter;
	else return 0;
}

function mysql_get_waiters_list() {
	$con = connect_to_db();
	$query = "SELECT * FROM waiters";
	$result = $con->query($query);
	//if ( is_resource($result ) {
	$i=0;
		//var_dump ( $result );
		while($res = $result->fetch_array()) {
			$uid = $res['uid'];
			$waiters_list[$i]['uid'] = $res['uid'];
			$waiters_list[$i]['username'] = $res['username'];
			$waiters_list[$i]['name'] = $res['name'];
			$waiters_list[$i]['lastname'] = $res['lastname'];
			$waiters_list[$i]['phone_number'] = $res['phone_number'];
			$waiters_list[$i]['password'] = $res['password'];
			$waiters_list[$i]['photo'] = $res['photo'];
			$waiters_list[$i]['state'] = $res['state'];
		//	$query = "SELECT photo FROM uploaded WHERE pid = $pid";
		//	$result1 = $link->query($query);
		//	$res = $result1->fetch_array();
		//edw 8a balw kai esoda apo paragelies
			$i++;
		}
//	}
	$con->close();
	if($i>0) return $waiters_list;
	else return 0;
}

function mysql_validate_login($username,$password) {
	$login['error'] = '';
	$con = connect_to_db();
	//if ($table = '' )
	$query = "SELECT uid FROM manager WHERE username LIKE '$username' AND password LIKE '$password'";
	$result = $con->query($query);
	$con->close();
	if($res = $result->fetch_array()) {
		$login['uid']  = $res['uid'];
		$login['username'] = $username;
	}
	else $login['error'] = "<p>Incorrect username or password. Please try again.</p>";
	return $login;
}

function mysql_validate_waiter_login($username,$password) {
	$login['error'] = '';
	$con = connect_to_db();
	//if ($table = '' )
	$query = "SELECT uid FROM waiters WHERE username LIKE '$username' AND password LIKE '$password'";
	$result = $con->query($query);
	$con->close();
	if($res = $result->fetch_array()) {
		$login['uid']  = $res['uid'];
		$login['username'] = $username;
		$login['state'] = "active";
		mysql_update_waiter_state( $login );
	}
	else $login['error'] = "<p>Incorrect username or password. Please try again.</p>";
	return $login;
}

//------------------------------------------------------ Mysql Products ------------------------------------------------------//

function mysql_get_products() {
	$con = connect_to_db();
	$query = "SELECT * FROM products";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$products_list[$i]['id'] = $res['id'];
		$products_list[$i]['name'] = $res['name'];
		$products_list[$i]['price'] = $res['price'];
		$products_list[$i]['description'] = $res['description'];
		$products_list[$i]['category_id'] = $res['category_id'];
		$i++;
	}
	$con->close();
	if($i>0) return $products_list;
	else return 0;
}

function mysql_get_products_list() {

	$con = connect_to_db();
	
	$query = "SELECT * FROM products";
	$result = $con->query($query);

	$i=0;
	
	while($res = $result->fetch_array()) {
		$products_list[$i]['id'] = $res['id'];
		$products_list[$i]['name'] = $res['name'];
		$products_list[$i]['price'] = $res['price'];
		$products_list[$i]['description'] = $res['description'];
		$products_list[$i]['category_id'] = $res['category_id'];
		$i++;
	}
	$con->close();
	return $products_list;
}

function mysql_insert_new_product($product) {
	$con = connect_to_db();
	$name = $product['name'];
	$price = $product['price'];
	$description = $product['description'];
	$category_id = $product['category_id'];
	$query = "INSERT INTO products(id, name, price, description, category_id ) VALUES (null,'$name', '$price', '$description', '$category_id')";
	$result = $con->query($query);
	$query = "SELECT * FROM products WHERE id = LAST_INSERT_ID();";
	$result = $con->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
	$con->close();
	if($row) return $row;
	else return 0;
}

function mysql_delete_product($id) {
	$con = connect_to_db();
	$query1 = "DELETE FROM associations WHERE associations.product=$id";
	$con->query($query1);
	$query2 = "DELETE FROM products WHERE id=$id";
	$con->query($query2);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_update_product($product) {
	$con = connect_to_db();
	$id = $product['id'];
	$name = $product['name'];
	$price = $product['price'];
	$description = $product['description'];
	$category_id = $product['category_id'];
	$query = "UPDATE products SET name = '$name', price = '$price', description = '$description', category_id = '$category_id' WHERE id = '$id'" ;	
	$result = $con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}
//------------------------------------------------------ Mysql features ------------------------------------------------------//

function mysql_get_features() {
	$con = connect_to_db();
	$query = "SELECT * FROM features";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$features_list[$i]['id'] = $res['id'];
		$features_list[$i]['name'] = $res['name'];
		$features_list[$i]['price'] = $res['price'];
		$features_list[$i]['type'] = $res['type'];
		$i++;
	}
	$con->close();
	if($i>0) return $features_list;
	else return 0;
}

function mysql_get_product_features( $product ) {
	$con = connect_to_db();
	$query = "SELECT * FROM features INNER JOIN associations ON features.id=associations.feature AND associations.product=$product";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$features_list[$i]['id'] = $res['id'];
		$features_list[$i]['name'] = $res['name'];
		$features_list[$i]['price'] = $res['price'];
		$features_list[$i]['type'] = $res['type'];
		$i++;
	}
	//print_r($features_list);
	$con->close();
	if($i>0) return $features_list;
	else return 0;
}

function mysql_delete_feature($id) {
	$con = connect_to_db();
	$query1 = "DELETE FROM associations WHERE associations.feature=$id";
	$con->query($query1);
	$query2 = "DELETE FROM features WHERE id=$id";
	$con->query($query2);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_insert_new_feature($feature) {
	$con = connect_to_db();
	$name = $feature['name'];
	$price = $feature['price'];
	$type = $feature['type'];
	$query = "INSERT INTO features(id, name, price, type ) VALUES (null,'$name', '$price', '$type')";
	$result = $con->query($query);
	$query = "SELECT * FROM features WHERE id = LAST_INSERT_ID();";
	$result = $con->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
	$con->close();
	if($row) return $row;
	else return 0;
}

function mysql_update_feature($feature) {
	$con = connect_to_db();
	$id = $feature["id"];
	$name = $feature["name"];
	$price = $feature['price'];
	$type = $feature['type'];
	$query = "UPDATE features SET name = '$name', price = '$price', type = '$type' WHERE id = '$id'" ;	
	$result = $con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function show_feature_type_options() {
	$con = connect_to_db();
	$query = "SELECT * FROM features";
	$result = $con->query($query);
	$i=0;
	/*while($res = $result->fetch_array()) {
		$PF_association_option[$i]['DisplayText'] = $res['type'];
		$PF_association_option[$i]['Value'] = $res['type'];
		$i++;
	}*/
	$PF_association_option[0]['DisplayText'] = "Μοναδικό";
	$PF_association_option[0]['Value'] = "Μοναδικό";
	$PF_association_option[1]['DisplayText'] = "Extra";
	$PF_association_option[1]['Value'] = "Extra";
	$i++;
	$con->close();
	return $PF_association_option;
}
//------------------------------------------------------ Mysql categories ------------------------------------------------------//

function mysql_update_category() {
	$con = connect_to_db();
	$id = $_POST["id"];
	$name = $_POST["name"];
	$description = $_POST['description'];
	$fid = $_POST['fid'];
	$query = "UPDATE categories
		SET name = '$name', description = '$description', fid = '$fid'
		WHERE id = '$id'" ;	
	$result = $con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_category_products( $category ) {
	$con = connect_to_db();
	$query = "SELECT * FROM products WHERE products.category_id=$category ORDER BY sells DESC";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$products_list[$i]['id'] = $res['id'];
		$products_list[$i]['name'] = $res['name'];
		$products_list[$i]['price'] = $res['price'];
		$products_list[$i]['description'] = $res['description'];
		$products_list[$i]['category_id'] = $res['category_id'];
		$i++;
	}
	$con->close();
	if($i>0) return $products_list;
	else return 0;
}

function mysql_get_categories() {
	$con = connect_to_db();
	$query = "SELECT * FROM categories";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$categories_list[$i]['id'] = $res['id'];
		$categories_list[$i]['name'] = $res['name'];
		$categories_list[$i]['description'] = $res['description'];
		$categories_list[$i]['fid'] = $res['fid'];
		$i++;
	}
	if($i>0) return $categories_list;
	else return 0;
}

//Get parent categories
function mysql_get_Pcategories() {
	$con = connect_to_db();
	$query = "SELECT * FROM categories WHERE fid=-1";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$categories_list[$i]['id'] = $res['id'];
		$categories_list[$i]['name'] = $res['name'];
		$categories_list[$i]['description'] = $res['description'];
		$categories_list[$i]['fid'] = $res['fid'];
		$i++;
	}
	if($i>0) return $categories_list;
	else return 0;
}

function mysql_delete_category($id) {
	$con = connect_to_db();
	$query1 = "UPDATE categories
		SET fid=-1
		 WHERE categories.fid = '$id'";
	$con->query($query1);
	$query2 = "DELETE FROM categories WHERE id=$id";
	$con->query($query2);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_get_category_info($id) {
	$con = connect_to_db();
	$query = "SELECT name, description, fid FROM categories WHERE categories.id = $id";	
	$result = $con->query($query);
	$res = $result->fetch_array();
	$category_info['name'] = $res['name'];
	$category_info['description'] = $res['description'];
	$category_info['fid'] = $res['fid'];
	$con->close();
	return $res;
}

function mysql_insert_new_category($category) {
	$con = connect_to_db();
	$name = $category['name'];
	$description = $category['description'];
	$fid = $category['fid'];
	$query = "INSERT INTO categories(id, name, description, fid ) VALUES (null,'$name', '$description', '$fid')";
	$result = $con->query($query);
	$query = "SELECT * FROM categories WHERE id = LAST_INSERT_ID();";
	$result = $con->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
	$con->close();
	if($row) return $row;
	else return 0;
}
//------------------------------------------------------ Mysql orders ------------------------------------------------------//

function mysql_get_ordertable() {
	$con = connect_to_db();
	$query = "SELECT * FROM ordertable";
	$result = $con->query($query);
	$i=0;	
	while($res = $result->fetch_array()) {
		$orderTable[$i]['id'] = $res['id'];
		$orderTable[$i]['total'] = round( $res['total'], 2);
		$orderTable[$i]['countProducts'] = $res['countProducts'];
		$orderTable[$i]['waiter'] = $res['waiter'];
		$orderTable[$i]['datetime'] = $res['datetime'];
		$i++;
	}
    //$result = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($orderTable);
	$con->close();
	if($i>0) return $orderTable;	
	else return 0;
}

function mysql_get_ordertable_page( $StartIndex, $PageSize ) {
	$con = connect_to_db();
	$query = "SELECT * FROM ordertable LIMIT $StartIndex, $PageSize ";
	$result = $con->query($query);
	$i=0;	
	while($res = $result->fetch_array()) {
		$orderTable[$i]['id'] = $res['id'];
		$orderTable[$i]['total'] = $res['total'];
		$orderTable[$i]['countProducts'] = $res['countProducts'];
		$orderTable[$i]['waiter'] = $res['waiter'];
		$orderTable[$i]['datetime'] = $res['datetime'];
		$i++;
	}
    //$result = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($orderTable);
	$con->close();
	if($i>0) return $orderTable;
	else return 0;
}

function mysql_get_ordertable_page_sort( $sort, $StartIndex, $PageSize ) {
	$con = connect_to_db();
	$query = "SELECT * FROM ordertable ORDER BY $sort LIMIT $StartIndex, $PageSize ";
	$result = $con->query($query);
	$i=0;	
	while($res = $result->fetch_array()) {
		$orderTable[$i]['id'] = $res['id'];
		$orderTable[$i]['total'] = $res['total'];
		$orderTable[$i]['countProducts'] = $res['countProducts'];
		$orderTable[$i]['waiter'] = $res['waiter'];
		$orderTable[$i]['datetime'] = $res['datetime'];
		$i++;
	}
    //$result = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($orderTable);
	$con->close();
	if($i>0) return $orderTable;
	else return 0;
}

function mysql_get_ordertable_count() { 
	$con = connect_to_db();
	$query = "SELECT COUNT(*) AS TotalRecordCount FROM ordertable";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$TotalRecordCount = $res['TotalRecordCount'];
		$i++;
	}
	$con->close();
	if($i>0) return $TotalRecordCount;
	else return 0;
}

function mysql_get_order( $order_id ) {
	$con = connect_to_db();
	$query = "SELECT * FROM orders WHERE orders.order_id=$order_id";
	$result = $con->query($query);
	$i=0;	
	while($res = $result->fetch_array()) {
		$order[$i]['order_id'] = $res['order_id'];
		$order[$i]['product_name'] = $res['product_name'];
		$order[$i]['feature'] = $res['feature'];
		$order[$i]['extra_feature'] = $res['extra_feature'];
		$order[$i]['extra_text'] = $res['extra_text'];
		$order[$i]['pro_sum_price'] = round( $res['pro_sum_price'], 2);
		$order[$i]['extr_sum_price'] = round( $res['extr_sum_price'], 2);
		$order[$i]['count'] = $res['count'];
		$i++;
	}
	$con->close();
	if($i>0) return $order;	
	else return 0;
}

function mysql_insert_order($order) {
	$con = connect_to_db();
	$vals = array();
	$valss = array();
	$ord_id;
	$cond = 0;
	foreach($order as $orders) {
		foreach($orders as $classes => $class) {
		  	if (!is_array($class)) {
			    // Loop through not table objects...which in this case are ordertable objects
				if ( $classes == "waiter_username" ) $valss["waiter_username"] = $class;
				else if ( $classes == "datetime" ) $valss["datetime"] = $class;
				else if ( $classes == "total" ) $valss["total"] = $class;
				else if ( $classes == "countProducts" ) $valss["countProducts"] = $class;
			}
			else if (is_array($class)) {
				// Loop through table objects...which in this case are orders objects
			    foreach($class as $col => $val){
					if ( $col == "id" ) $vals["id"] = $val;
				    else if ( $col == "name" ) $vals["name"] = $val;
				    else if ( $col == "feature" ) $vals["feature"] = $val;
  				    else if ( $col == "extrafeatures" ) $vals["extrafeatures"] = $val;
				    else if ( $col == "extratext" ) $vals["extratext"] = $val;
				    else if ( $col == "count" ) $vals["count"] = $val;
				    else if ( $col == "sum_price" ) $vals["sum_price"] = $val;
				    else if ( $col == "f_sum_price") $vals["f_sum_price"] = $val;
   				}
   				if($cond==0) {
   				//print_r($valss);
   				//print_r($vals);
   				//insert into ordertable
   				$query1 = "INSERT INTO ordertable(id, total, countProducts, waiter, datetime ) VALUES (NULL, '$valss[total]', '$valss[countProducts]', '$valss[waiter_username]', '$valss[datetime]' )";
			    $con->query($query1);
			    $ord_id = $con->insert_id;
				//echo $ord_id ;
				$cond=1;
				}
				//insert into orders
				$query2 = "INSERT INTO orders(order_id, product_id, product_name, feature, extra_feature, extra_text, pro_sum_price, extr_sum_price, count, waiter )
				   	VALUES ( '$ord_id', '$vals[id]', '$vals[name]', '$vals[feature]', '$vals[extrafeatures]', '$vals[extratext]', '$vals[sum_price]', '$vals[f_sum_price]', '$vals[count]', '$valss[waiter]')";
			    $con->query($query2);
			}
		}
	}
	$query = "SELECT product_id, sum(count) FROM orders GROUP BY product_id ORDER BY sum(count) DESC";
	$result = $con->query($query);	
	$i=0;
	while($res = $result->fetch_array()) {
		$product_id = $res['product_id'];
		$sells = $res['sum(count)'];
		$query3 = "UPDATE products
		SET sells='$sells'
		 WHERE products.id = '$product_id'";
		$i++;
		$con->query($query3);
	}
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

// Waiter gets his own orders list
function mysql_get_myOrdertable( $waiter ) {
	$con = connect_to_db();
	$query = "SELECT * FROM ordertable WHERE '$waiter' = ordertable.waiter ORDER BY datetime DESC";
	$result = $con->query($query);
	$i=0;	
	while($res = $result->fetch_array()) {
		$orderTable[$i]['id'] = $res['id'];
		$orderTable[$i]['total'] = $res['total'];
		$orderTable[$i]['countProducts'] = $res['countProducts'];
		$orderTable[$i]['waiter'] = $res['waiter'];
		$orderTable[$i]['datetime'] = $res['datetime'];
		$i++;
	}
    //$result = $stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($orderTable);
	$con->close();
	if($i>0) return $orderTable;
	else return 0;
}

function mysql_get_PF_association ( $feature ) {
	$con = connect_to_db();
	$query = "SELECT * FROM associations WHERE '$feature' = associations.feature";
	$result = $con->query($query);
	$i=0;
	$j=0;
	while($res = $result->fetch_array()) {
		$associations[$i]['feature'] = $res['feature'];
		$associations[$i]['name'] = $res['product'];	// the name is the product id ! jtable option list helps out
		$associations[$i]['product_id'] = $res['product'];
		$i++;
	}
	$con->close();
	if($i>0) return $associations;
	else return 0;
}

function insertPF_association( $feature, $product ) {
	$con = connect_to_db();
	$query = "SELECT * FROM associations";
	$result = $con->query($query);
	$exists = false;
	while($res = $result->fetch_array()) {
		if(($feature == $res['feature']) && ($product == $res['product'])) {
			$exists = true;
			break;
		}
	}
	if( $exists == false ) {
		$query = "INSERT INTO associations(feature, product) VALUES ('$feature', '$product')";
		$result = $con->query($query);
		$query = "SELECT * FROM associations WHERE '$feature' = associations.feature";
		$result = $con->query($query);
		//$inserted = $result->fetch_array(MYSQLI_BOTH);
		//$row = array();
		//$row['name'] = $inserted['product'];
		//print_r($row['name']);
	}
	$row = array();
	$row['product_id'] = $product;
	$row['name'] = $product;
	$con->close();
	if($row) return $row;
	else return 0;
}

function mysql_deletePF_association( $feature, $product ) {
	$con = connect_to_db();
	$query = "DELETE FROM associations WHERE feature=$feature AND product=$product";
	$con->query($query);
	$result = $con->affected_rows;
	$con->close();
	return $result >= 0;
}

function mysql_export_orders_xml() {
	$x=new XMLWriter();
	$x->openMemory();
	$x->startDocument('1.0','UTF-8');

    $x->startElement('orderTable');
	$order_table = mysql_get_ordertable();
		
	$doc = new DOMDocument('1.0');
	// we want a nice output
	$doc->formatOutput = true;
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="downloaded.xml"');
	$ordertable = $doc->createElement('ordertable');
	$ordertable = $doc->appendChild($ordertable);

	foreach ($order_table as &$value) {

		$Order = $doc->createElement('Order');
		$Order = $ordertable->appendChild($Order);
	    
	    $id = $doc->createElement('id');
		$id = $Order->appendChild($id);

		$text = $doc->createTextNode($value['id']);
		$text = $id->appendChild($text);

		$total = $doc->createElement('total');
		$total = $Order->appendChild($total);

		$text = $doc->createTextNode($value['total']);
		$text = $total->appendChild($text);

		$num_of_products = $doc->createElement('num_of_products');
		$num_of_products = $Order->appendChild($num_of_products);

		$text = $doc->createTextNode($value['countProducts']);
		$text = $num_of_products->appendChild($text);

		$waiter = $doc->createElement('waiter');
		$waiter = $Order->appendChild($waiter);

		$text = $doc->createTextNode($value['waiter']);
		$text = $waiter->appendChild($text);

		$order_products = mysql_get_order($value['id']);

		$Products = $doc->createElement('Products');
		$Products = $Order->appendChild($Products);
		
		foreach ($order_products as &$value) {

	    	$product_name = $doc->createElement('product_name');
			$product_name = $Products->appendChild($product_name);

			$text = $doc->createTextNode($value['product_name']);
			$text = $product_name->appendChild($text);

			$id = $doc->createElement('features');
			$id = $Products->appendChild($id);

			$text = $doc->createTextNode($value['feature']);
			$text = $id->appendChild($text);

			$id = $doc->createElement('quantity');
			$id = $Products->appendChild($id);

			$text = $doc->createTextNode($value['count']);
			$text = $id->appendChild($text);
		}
	}	
	echo $doc->saveXML() . "\n";
	
	
}

function mysql_export_excel() {
	// require the PHPExcel file
	require 'C:/xampp/htdocs/web_based_ordering_system/project/PHPExcel_1.7.9_doc/Classes/PHPExcel.php';
	// simple query
	$con = connect_to_db();
	$query = "SELECT * FROM ordertable";
	$headings = array('Α/Α','Σύνολο', 'Ποσότητα','Σερβιτόρος','Ημερομηνία');
	$fields = array ('id', 'total', 'countProducts', 'waiter', 'datetime');
	if ($result = $con->query($query) ) {
		// Create a new PHPExcel object
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getActiveSheet()->setTitle('Λίστα Παραγγελιών');
		$rowNumber = 1;
		$col = 'A';
		foreach($headings as $heading) {
			$objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$heading);
			$col++;
		}
		//$waiter_info = mysql_get_waiter_option_list();	    
		// Loop through the result set
		$rowNumber = 2;
		while ($row = $result->fetch_array()) {
     	    $col = 'A';
			for ($i=0; $i<sizeof($fields); $i++) {
				$objPHPExcel->getActiveSheet()->setCellValue($col.$rowNumber,$row[$fields[$i]]);
				$objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
				$col++;
			}
			$rowNumber++;
		}
		$objPHPExcel->getActiveSheet()->freezePane('A2');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="orders.xls"');
		header('Cache-Control: max-age=0');
		$objWriter->save('php://output');
		exit();
	}
}

function mysql_get_incomes_statistics( $Period ) {
	$con = connect_to_db();
	if( $Period == "incomes-statistics_day")
		$query = "SELECT YEAR(datetime), MONTH(datetime), MONTHNAME(datetime), DAY(datetime), DAYNAME(datetime), sum( total ), sum( countProducts ) FROM ordertable GROUP BY YEAR(datetime), MONTH(datetime), DAY(datetime)";
	else if( $Period == "incomes-statistics_month")
		$query = "SELECT YEAR(datetime), MONTH(datetime), MONTHNAME(datetime), sum( total ), sum( countProducts ) FROM ordertable GROUP BY YEAR(datetime), MONTH(datetime)";
	else if( $Period == "incomes-statistics_year")
		$query = "SELECT YEAR(datetime), sum( total ), sum( countProducts ) FROM ordertable GROUP BY YEAR(datetime)";
	else
		$query = "SELECT sum( total ), sum( countProducts ) FROM ordertable GROUP BY YEAR(datetime), MONTH(datetime), DAY(datetime)";
	$result = $con->query($query);	
	$i=0;
	while($res = $result->fetch_array()) {
		switch ( $Period ) {
			case "incomes-statistics_day":
				$statistics[$i]['year'] = $res["YEAR(datetime)"];	
				$statistics[$i]['month'] = "(".$res["MONTH(datetime)"].")"." ".$res["MONTHNAME(datetime)"];
				$statistics[$i]['day'] = "(".$res["DAY(datetime)"].")"." ".$res["DAYNAME(datetime)"];
	        break;
	    	case "incomes-statistics_month":
				$statistics[$i]['year'] = $res["YEAR(datetime)"];
				$statistics[$i]['month'] = "(".$res["MONTH(datetime)"].")"." ".$res["MONTHNAME(datetime)"];
				$statistics[$i]['day'] = "-";
			break;
	    	case "incomes-statistics_year":
				$statistics[$i]['year'] = $res["YEAR(datetime)"];	
				$statistics[$i]['month'] = "-";
				$statistics[$i]['day'] = "-";
	        break;
		}
		$statistics[$i]['total'] = round( $res['sum( total )'], 2 );
		$statistics[$i]['countProducts'] = $res['sum( countProducts )'];
		$i++;
	}
	$con->close();
	if($i>0) return $statistics;
	else return 0;
}

function mysql_export_incomes_statistics_xml( $period ) {
	$x=new XMLWriter();
	$x->openMemory();
	$x->startDocument('1.0','UTF-8');

    $x->startElement('Incomes_Table');
	$incomes_statistics = mysql_get_incomes_statistics( $period );
		
	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;

	$incomes_statistics_table = $doc->createElement($period.'_incomes');
	$incomes_statistics_table = $doc->appendChild($incomes_statistics_table);

	foreach ($incomes_statistics as &$value) {

		$incomes_row = $doc->createElement('incomes_row');
		$incomes_row = $incomes_statistics_table->appendChild($incomes_row);

		$year = $doc->createElement('year');
		$year = $incomes_row->appendChild($year);

		$text = $doc->createTextNode($value['year']);
		$text = $year->appendChild($text);

		$month = $doc->createElement('month');
		$month = $incomes_row->appendChild($month);

		$text = $doc->createTextNode($value['month']);
		$text = $month->appendChild($text);

		$day = $doc->createElement('day');
		$day = $incomes_row->appendChild($day);

		$text = $doc->createTextNode($value['day']);
		$text = $day->appendChild($text);

		$total = $doc->createElement('total');
		$total = $incomes_row->appendChild($total);

		$text = $doc->createTextNode($value['total']);
		$text = $total->appendChild($text);

		$countProducts = $doc->createElement('countProducts');
		$countProducts = $incomes_row->appendChild($countProducts);

		$text = $doc->createTextNode($value['countProducts']);
		$text = $countProducts->appendChild($text);
	}	
	echo $doc->saveXML() . "\n";
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="downloaded.xml"');
}

function mysql_get_products_statistics( $ProductsDisplay, $numOfProds ) {
	$con = connect_to_db();
	if( $ProductsDisplay == "most_sold")
		$query = "SELECT product_name, sum(pro_sum_price), sum(count) FROM orders GROUP BY product_name ORDER BY sum(count) DESC LIMIT $numOfProds";
	else if( $ProductsDisplay == "least_sold")
		$query = "SELECT product_name, sum(pro_sum_price), sum(count) FROM orders GROUP BY product_name ORDER BY sum(count) ASC LIMIT $numOfProds";
	$result = $con->query($query);	
	$i=0;
	while($res = $result->fetch_array()) {
		$statistics[$i]['product_name'] = $res['product_name'];
		$statistics[$i]['total'] = round( $res['sum(pro_sum_price)'], 2);
		$statistics[$i]['countProducts'] = $res['sum(count)'];
		$i++;
	}
	$con->close();
	if($i>0) return $statistics;
	else return 0;
}

function mysql_export_products_statistics_xml( $ProductsDisplay, $numOfProds ) {
	$x=new XMLWriter();
	$x->openMemory();
	$x->startDocument('1.0','UTF-8');

    $x->startElement('Products_Table');
	$products_statistics = mysql_get_products_statistics( $ProductsDisplay, $numOfProds );
		
	$doc = new DOMDocument('1.0');
	$doc->formatOutput = true;

	$products_statistics_table = $doc->createElement($ProductsDisplay.'_products');
	$products_statistics_table = $doc->appendChild($products_statistics_table);

	foreach ($products_statistics as &$value) {

		$product_row = $doc->createElement('product_row');
		$product_row = $products_statistics_table->appendChild($product_row);

		$product = $doc->createElement('product');
		$product = $product_row->appendChild($product);

		$text = $doc->createTextNode($value['product_name']);
		$text = $product->appendChild($text);

		$total = $doc->createElement('total');
		$total = $product_row->appendChild($total);

		$text = $doc->createTextNode($value['total']);
		$text = $total->appendChild($text);

		$countProducts = $doc->createElement('countProducts');
		$countProducts = $product_row->appendChild($countProducts);

		$text = $doc->createTextNode($value['countProducts']);
		$text = $countProducts->appendChild($text);
	}	
	echo $doc->saveXML() . "\n";
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename="downloaded.xml"');
}


////////////// MYSQL OPTIONS LIST ///////////////////////

function mysql_get_category_option_list() {
	$con = connect_to_db();
	$query = "SELECT * FROM categories";
	$result = $con->query($query);
	$categories_option[0]['DisplayText'] = "-";
	$categories_option[0]['Value'] = -1;
	$i=1;
	while($res = $result->fetch_array()) {
		$categories_option[$i]['DisplayText'] = $res['name'];
		$categories_option[$i]['Value'] = $res['id'];
		$i++;
	}
	$con->close();
	return $categories_option;
}

function show_PF_association_options() {
	$con = connect_to_db();
	$query = "SELECT * FROM products";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$PF_association_option[$i]['DisplayText'] = $res['name'];
		$PF_association_option[$i]['Value'] = $res['id'];
		$i++;
	}
	$con->close();
	return $PF_association_option;
}

function mysql_get_waiter_option_list() {
	$con = connect_to_db();
	$query = "SELECT * FROM waiters";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$waiters_option[$i]['DisplayText'] = $res['username'];
		$waiters_option[$i]['Value'] = $res['uid'];
		$i++;
	}
	$con->close();
	return $waiters_option;
}

function mysql_get_product_option_list() {
	$con = connect_to_db();
	$query = "SELECT * FROM products";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$products_option[$i]['DisplayText'] = $res['name'];
		$products_option[$i]['Value'] = $res['id'];
		$i++;
	}
	$con->close();
	return $products_option;
}

function mysql_get_feature_option_list() {
	$con = connect_to_db();
	$query = "SELECT * FROM features";
	$result = $con->query($query);
	$i=0;
	while($res = $result->fetch_array()) {
		$features_option[$i]['DisplayText'] = $res['name'];
		$features_option[$i]['Value'] = $res['id'];
		$i++;
	}
	$con->close();
	return $features_option;
}


//------------------------------------- ACTIONS -------------------------------------//


if(!empty($_GET["action"])) {
		
//------------------------------------- HANDLE WAITER -------------------------------------//

	if($_GET["action"] == "list") {
		$rows = mysql_get_waiters_list();	
			echo json_encode($rows);	
	}
	
	if($_GET["action"] == "create_waiter") {
		$jTableResult = array();
		if( (isset($_POST["username"])) && (isset($_POST["name"])) && (isset($_POST["lastname"])) &&
		    (isset($_POST["phone_number"])) && (isset($_POST["password"])) ) {	
		    $data = $_POST;
			$create = mysql_insert_new_waiter( $data );
			if($create) {
				$jTableResult = mysql_get_waiter($data['username']);
	 			echo json_encode($jTableResult);
			}
			else {
				$jTableResult['Result'] = "Error: request to db failed";
				print json_encode($jTableResult);
			}	
	    }
	    else{
			$jTableResult['Result'] = "Error: data not send correctly";
	    	print json_encode($jTableResult);
	    }
	}

	if($_GET["action"] == "edit_waiter") {
		$jTableResult = array();
		if( (isset($_POST["uid"])) && (isset($_POST["username"])) && (isset($_POST["name"])) && (isset($_POST["lastname"])) &&
		    (isset($_POST["phone_number"])) && (isset($_POST["password"])) && (isset($_POST["photo"])) ){
			$data = $_POST ;
			$edit = mysql_edit_waiter($data);
			if($edit){
				$jTableResult = mysql_get_waiter($data['username']);
	 			echo json_encode($jTableResult);
			}
			else {
				$jTableResult['Result'] = "Error: request to db failed";
				print json_encode($jTableResult);
			}
	    }
	    else{
			$jTableResult['Result'] = "Error: data not send correctly";
	    	print json_encode($jTableResult);
	    }
	}

	if($_GET["action"] == "delete_waiter") {
			$record = $_POST;
			$dbResponse = mysql_delete_waiter( $record );
			$jTableResult = array();
			if( $dbResponse == true ) {
				$jTableResult['Result'] = "Record Deleted";
			} else {
				$jTableResult['Result'] = "Error Deleting";
			}
			print json_encode($jTableResult);
	}

//------------------------------------- HANDLE WAITER //-------------------------------------

/////////////// PRODUCTS /////////////////////////

///// --- OK ->
	if($_GET["action"] == "products") {
		$jTableResult = array();
		$rows = mysql_get_products();	
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		}
		else { 
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}

///// --- OK ->	
	if($_GET["action"] == "create_product") {
		$jTableResult = array();
		$row = mysql_insert_new_product($_POST);
		//$row = mysql_fetch_array($result);
		if($row) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Record'] = $row;
		}
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

///// --- OK ->	
	if($_GET["action"] == "update_product") {
		$jTableResult = array();
		$row = mysql_update_product($_POST);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

///// --- OK ->			
	if($_GET["action"] == "delete_product") {
		$jTableResult = array();
		$row = mysql_delete_product($_POST['id']);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}
///////////////////// Features  /////////////////////
///// --- OK ->			

	if($_GET["action"] == "create_feature") {
		$jTableResult = array();
		$row = mysql_insert_new_feature($_POST);
		if($row) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Record'] = $row;
		}
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

///// --- OK ->			
	if($_GET["action"] == "update_feature") {
		$jTableResult = array();
		$row = mysql_update_feature($_POST);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

	if($_GET["action"] == "show_feature_type_options") {
		$jTableResult = array();
		$row = show_feature_type_options();
		if($row) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Options'] = $row;
		}
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}
	
	
///// --- OK ->	
	if($_GET["action"] == "delete_feature") {
		$jTableResult = array();
		$row = mysql_delete_feature($_POST['id']);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

	if($_GET["action"] == "getPF_association") {
		$jTableResult = array();
		$rows = mysql_get_PF_association( $_GET['id'] );
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
			//$jTableResult['Result'] = "ERROR";
			//$jTableResult['Message'] = "No records exist";
		}
		echo json_encode($jTableResult);
	}
	
	if( ($_GET["action"] == "show_PF_association_options") || ($_GET["action"] == "update_PF_association") ) {
		$jTableResult = array();
		$row = show_PF_association_options();
		$jTableResult['Result'] = "OK";
		$jTableResult['Options'] = $row;
		//$jTableResult['Result'] = "ERROR";
		print json_encode($jTableResult);
	}

	if($_GET["action"] == "create_PF_association") {
		$jTableResult = array();
		$row = insertPF_association($_GET['id'], $_POST['name']);
		if($row) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Record'] = $row;
		}
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

	if($_GET["action"] == "deletePF_association") {
		$jTableResult = array();
		$row = mysql_deletePF_association($_GET['id'], $_POST['product_id']);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

///////////////////// CATEGORIES  /////////////////////

///// --- OK ->
	if($_GET["action"] == "create_category") {
		$jTableResult = array();
		$row = mysql_insert_new_category($_POST);
		if($row) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Record'] = $row;
		}
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}
///// --- OK ->
	if($_GET["action"] == "update_category") {
		$jTableResult = array();
		$row = mysql_update_category($_POST);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}
///// --- OK ->	
	if($_GET["action"] == "delete_category") {
		$jTableResult = array();
		$row = mysql_delete_category($_POST['id']);
		if($row) $jTableResult['Result'] = "OK";
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}


///////////////////// Orders /////////////////////

	if($_GET["action"] == "central_orderTable_list") {
		$jTableResult = array();
		$rows = mysql_get_ordertable_page_sort( "id DESC", 0, 5 );			
		$TotalRecordCount = mysql_get_ordertable_count();
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
			$jTableResult['TotalRecordCount'] = $TotalRecordCount;
		}
		else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}

///// --- OK ->
	if($_GET["action"] == "orderTable_list") {
		$jTableResult = array();

		//if( isset($_GET["jtSorting"]) ) $rows = mysql_get_ordertable_sort( $_GET["jtSorting"] );
		//else $rows = mysql_get_ordertable();

		if( isset($_GET["jtStartIndex"]) && isset($_GET["jtPageSize"]) && isset($_GET["jtSorting"]) ) $rows = mysql_get_ordertable_page_sort( $_GET["jtSorting"], $_GET["jtStartIndex"], $_GET["jtPageSize"] );
		else if( isset($_GET["jtStartIndex"]) && isset($_GET["jtPageSize"]) ) $rows = mysql_get_ordertable_page( $_GET["jtStartIndex"], $_GET["jtPageSize"] );

		else $rows = mysql_get_ordertable();
		
		$TotalRecordCount = mysql_get_ordertable_count();
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
			$jTableResult['TotalRecordCount'] = $TotalRecordCount;
		}
		else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}

///// --- OK ->
	if($_GET["action"] == "orders") {
		$jTableResult = array();
		$rows = mysql_get_order( $_GET['id'] );
		$TotalRecordCount = mysql_get_ordertable_count();
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
			$jTableResult['TotalRecordCount'] = $TotalRecordCount;
		}
		else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}
///

/////////////// STATISTICS->incomes /////////////////////////

///// --- OK ->
	if( $_GET["action"] == "incomes-statistics_day" ||
	 	$_GET["action"] == "incomes-statistics_month" ||
	 	$_GET["action"] == "incomes-statistics_year" ) {
		
		$jTableResult = array();
		$rows = mysql_get_incomes_statistics($_GET["action"]);	
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		}
		else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}

	if( $_GET["action"] == "export-statistics_incomes_xml" ) {
		mysql_export_incomes_statistics_xml($_GET["period"]);	
	}
	

///// --- OK ->
	if( $_GET["action"] == "products-statistics" ) {

		$jTableResult = array();
		$rows = mysql_get_products_statistics($_GET["display"], $_GET["numofprods"] );	
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		}
		else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}

	if( $_GET["action"] == "export-statistics_products_xml" ) {
		mysql_export_products_statistics_xml($_GET["display"], $_GET["numofprods"] );
	}
	

//------------------------------------ WAITER PART ------------------------------------//
///// --- OK ->
	if($_GET["action"] == "categories") {
		$jTableResult = array();
		$rows = mysql_get_categories();	
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}

	if($_GET["action"] == "Pcategories") {
		$jTableResult = array();
		$rows = mysql_get_Pcategories();	
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		}
		echo json_encode($jTableResult);
	}
///// --- OK ->
	if($_GET["action"] == "category_products") {
		$jTableResult = array();
		$rows = mysql_category_products($_POST["id"]);
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

///// --- OK ->
	if($_GET["action"] == "features") {
		$jTableResult = array();
		$rows = mysql_get_features();
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		 }
		echo json_encode($jTableResult);
	}

	if($_GET["action"] == "product_features") {
		$jTableResult = array();
		$rows = mysql_get_product_features( $_POST["id"] );
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else {
			$jTableResult['Result'] = "ERROR";
			$jTableResult['Message'] = "Δεν υπάρχουν καταχωρήσεις";
		 }
		echo json_encode($jTableResult);
	}

///// --- OK ->
	if($_GET["action"] == "submit_order") {
		//print_r($_POST);
		$jTableResult = array();
		$rows = mysql_insert_order( $_POST );
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

	if($_GET["action"] == "getMyOrdersTable") {
		$jTableResult = array();
		//echo $_POST["id"];
		$rows = mysql_get_myOrdertable($_POST["id"]);
		if($rows) {
			$jTableResult['Result'] = "OK";
			$jTableResult['Records'] = $rows;
		} else $jTableResult['Result'] = "ERROR";
		echo json_encode($jTableResult);
	}

	///////////////////// SHOW OPTIONS ////////////////////

	if($_GET["action"] == "show_category") {
		$row = mysql_get_category_option_list();
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Options'] = $row;
		//$jTableResult['Result'] = "ERROR";
		print json_encode($jTableResult);
	}		

	if($_GET["action"] == "show_waiter") {
		$row = mysql_get_waiter_option_list();
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Options'] = $row;
		//$jTableResult['Result'] = "ERROR";
		print json_encode($jTableResult);
	}		

	if($_GET["action"] == "show_product") {
		$row = mysql_get_product_option_list();
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Options'] = $row;
		//$jTableResult['Result'] = "ERROR";
		print json_encode($jTableResult);
	}		

	if($_GET["action"] == "show_feature") {
		$row = mysql_get_feature_option_list();
		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Options'] = $row;
		//$jTableResult['Result'] = "ERROR";
		print json_encode($jTableResult);
	}

	if($_GET["action"] == "export-orders_xml") {
	 	//mysql_export_excel();
		mysql_export_orders_xml();
	}			
}

?>