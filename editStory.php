<!DOCTYPE HTML>
<html>
	<head>
		<title> Edit story </title>
		<meta charset="utf-8">
		<style type="text/css">
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
        
		if(isset($_POST['back'])){
			header("Location: news.php");
			exit;
		}else if(isset($_POST['submit'])){
            $storyID=$_SESSION['storyID'];
			$story_title=htmlspecialchars($_POST['title']);
            
			if($story_title==''){
				echo "Title is empty!";
			}
            else{
				$story_content=htmlspecialchars($_POST['content']);
				$story_link=htmlspecialchars($_POST['link']);
				if ($story_link!=''&&!filter_var(filter_var($story_link,FILTER_SANITIZE_URL),FILTER_VALIDATE_URL)){
					echo("$story_link is not valid!");
				}
                else{
                    // Update story_info database
					$stmt=$mysqli->prepare("update story_info set story_header=?,story_content=? where story_id=?");
					if(!$stmt){
						printf("Query Failed: %s\n", $mysqli->error);
						exit;
					}
                    
                    $stmt->bind_param('ssi', $story_title, $story_content, $storyID);
					$stmt->execute();
					$stmt->close();
					
					
                    // Update story_link database if there is any
					// echo $story_link;
					if($story_link!=''){
						$stmt=$mysqli->prepare("update story_link set story_link=? where story_id=?");
						if(!$stmt){
							printf("Query Failed: %s\n", $mysqli->error);
							exit;
						}
						$stmt->bind_param('si', $story_link, $storyID);
						$stmt->execute();
						$stmt->close();
                    }
					header("Location:news.php");
					exit;
				}
            }
        }
        //session_destroy();
	?></p>
	
	<form action="editStory.php" method="POST">
		<?php
        require_once('database.php');
        //session_start();
        // Search story_info database
        $storyID=$_SESSION['storyID'];
        $stmt=$mysqli->prepare("select story_header,story_content from story_info where story_id=?");
        if(!$stmt){
            printf("Query failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $storyID);
        $stmt->execute();
        $stmt->bind_result($story_title, $story_content);
        $stmt->fetch();
        $stmt->close();
        
        // Search story_link database
        $stmt=$mysqli->prepare("select story_link from story_link where story_id=?");
        if(!$stmt){
            printf("Query failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i', $storyID);
        $stmt->execute();
        $stmt->bind_result($story_link);
        $stmt->fetch();
        $stmt->close();
        
        // Display original story_info to edit
        printf("<input type='hidden' name='token' value='%s'>
                <p>
                    <label>Title:</label><br>
                    <textarea name='title' rows=1>%s</textarea>
                </p>
                <p>
                    <label>Content:</label><br>
                    <textarea name='content' rows=5>%s</textarea>
                </p>
                <p>
                    <label>Link(optional):</label><br>
                    <textarea name='link' rows=1>%s</textarea>
                </p>",
                htmlspecialchars($_SESSION['token']),
                htmlspecialchars($story_title),
                htmlspecialchars($story_content),
                htmlspecialchars($story_link));
		
		?>
		<p>
			<input type="submit" name ="submit" value="Submit" />
			<input type="submit" name ="back" value ="Back"/>
		</p>
	</form>
	</body>
</html>