<?php

include_once 'mysql.php';

	function validate_login($post)
	{
		$login = mysql_validate_login($post['username'],$post['password']);
		
		if($login['error'] == '')
		{
			//$_SESSION['manager']['uid']=$login['uid'];
			//$_SESSION['manager']['username'] = $login['username'];
			$_SESSION['uid']=$login['uid'];
		}
		return $login['error'];
	}

	function insert_new_waiter($waiter_info,  $files, $session)
	{
		echo $waiter_info['username'];
		$errMsg = '';
		if(isset($waiter_info['username']) && isset($waiter_info['password']) && isset($waiter_info['cpassword']) && isset($waiter_info['name']) && isset($waiter_info['lastname'])){

			if($waiter_info['username'] === '') 
			{
				$errMsg = "Παρακαλώ εισάγετε username";
				goto error;
			}
			if($waiter_info['name'] === '') 
			{
				$errMsg = "Παρακαλώ εισάγετε όνομα";
				goto error;
			}
			if($waiter_info['lastname'] === '') 
			{
				$errMsg = "Παρακαλώ εισάγετε επώνυμο";
				goto error;
			}
			if($waiter_info['password'] === '') 
			{
				$errMsg = "Παρακαλώ εισάγετε κωδικό";
				goto error;
			}
			if($waiter_info['cpassword'] === '') 
			{
				$errMsg = "Παρακαλώ επιβεβαιώστε τον κωδικό";
				goto error;
			}
			if(!( $waiter_info['password'] == $waiter_info['cpassword']))
			{
				$errMsg = "Οι κωδικοί δεν ταιριάζουν";
				goto error;
			}
			if( $errMsg =='')
			{
				//edw prepei na ftiaxtei me thn eikona
				if (isset($files))
				{


					//upload_image($waiter_info, $files);
				}
				else mysql_insert_new_waiter($waiter_info, $files);
				echo 'Εισαγωγή επιτυχής';
				echo '<script type="text/javascript">window.location = "waiters.php?action=view";</script>';
			}
			else
			{
				goto error;
			}
			error:
					echo $errMsg ;
					get_add_waiter_form();
		}
		else
		{
		$errMsg = "Παρακάλώ συμπληρώστε όλα τα πεδία";
		get_add_waiter_form();
		echo $errMsg ;
		}
	}



if(!empty($_GET["action"])) {

	if( ($_GET["action"] == "upload_image") && (isset($_FILES)) ) {


		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);
		if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < 5242880) // 5 Mbyte
		&& in_array($extension, $allowedExts))
		  {
		  if ($_FILES["file"]["error"] > 0)
		    {
		    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		    }
		  else
		    {
		    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		    echo "Type: " . $_FILES["file"]["type"] . "<br>";
		    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

		    if (file_exists("C:/xampp/htdocs/web_based_ordering_system/project/images/waiters/" . $_FILES["file"]["name"]))
		      {
		      echo $_FILES["file"]["name"] . " already exists. ";
		      }
		    else
		      {
		      move_uploaded_file($_FILES["file"]["tmp_name"], "C:/xampp/htdocs/web_based_ordering_system/project/images/waiters/" . $_FILES["file"]["name"]);
		      echo "Stored in: " . "C:/xampp/htdocs/web_based_ordering_system/project/images/waiters/" . $_FILES["file"]["name"];
		      $photo = "images/waiters/".$_FILES["file"]["name"];
		      mysql_insert_waiter_image( $_POST['waiter_uid'], $photo );
		      }
		    }
		  }
		else
		  {
		  echo "Invalid file";
		  }
	}
	else echo "no files submitted";
    header("location:../../waiters.php");

}
	
/*
function upload_image($waiter_info, $files)
	{	
		$error = '';
		$tmp = "C:/xampp/htdocs/web_based_ordering_system/project/temp/";
		$imagetype = explode('.',$files['path']['name']);
		$imagetype = ".".$imagetype[count($imagetype)-1];
		$file = uniqid(rand(10000,99999),true).$imagetype;
		$tmpfilename = $tmp.$file;		
		move_uploaded_file($files['path']['tmp_name'],$tmpfilename);
		$final_filename = "images/waiters/".$file;
		copy($tmpfilename, $final_filename);
		mysql_insert_waiter_image($waiter_info,$final_filename);
		unlink($tmpfilename);
		unset($_FILES);
		return $error;
	}
	*/



?>