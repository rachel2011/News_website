<!DOCTYPE HTML>
<html>
	<head>
		<title> Edit comment </title>
		<meta charset="utf-8">
		<style type="text/css" >
		body{
			background-color:#D2B4DE;	
            position: absolute;
            left: 50%;
            width: 300px;
            margin-left: -150px;
			font-size:25px;
			}
		</style>
	</head>
	
	<body>
	<p><?php
        require_once('database.php');
        // This part is responsible for displaying input validation infomation
        session_start();
        // Security check
		if(isset($_POST['token'])&& $_SESSION['token']!=$_POST['token']){
			die("404");
		}
        $commentID=$_SESSION['commentID'];
		if(isset($_POST['back'])){
			header("Location: readStory.php");
			exit;
		}else if(isset($_POST['submit'])){
			$comment_content=htmlspecialchars($_POST['content']);
			if($comment_content==''){
				echo "Comment is empty!";
			}
            else{
				
                // Update comment_info database
                $stmt=$mysqli->prepare("update comment_info set comment_content=? where comment_id=?");
                if(!$stmt){
                    printf("Query Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('si',$comment_content, $commentID);
                $stmt->execute();
                $stmt->close();
                
                // Edit comment successful and go back to readStory.php 
                header("Location:readStory.php");
                exit;
				
            }
        }
        //session_destroy();
	?></p>
	
	<form action="editComment.php" method="POST">
		<?php
        require_once('database.php');
        // session_start();
        
        // Search comment_info database
        $stmt=$mysqli->prepare("select comment_content from comment_info where comment_id=?");
        if(!$stmt){
            printf("Query failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i',$commentID);
        $stmt->execute();
        $stmt->bind_result($comment_content);
        $stmt->fetch();
        $stmt->close();
        
        // Display original comment_info to edit
        printf("<input type='hidden' name='token' value=%s>
                <p>
                    <label>Content:</label><br>
                    <textarea name='content' rows=10>%s</textarea>
                </p>",
                htmlspecialchars($_SESSION['token']),
                htmlspecialchars($comment_content));
		
		?>
		<p>
			<input type="submit" name ="submit" value="Submit" />
			<input type="submit" name ="back" value ="Back"/>
		</p>
	</form>
	</body>
</html>