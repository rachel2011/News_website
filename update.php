<?php
session_start();
require 'database.php';

//update user profile

if($_POST['form_type'] == 'updateUser'){
    
		$newPassword = crypt($_POST['updatePwd']);
		$stmt = $mysqli->prepare("update accounts set password=? where user_name=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		 
		$stmt->bind_param('ss', $newPassword, $_SESSION['userName']);
		$stmt->execute();
        $stmt->close();
       
        printf("Update successfully<a href='profile.php'>Go back</a>");
	}
 ?>   