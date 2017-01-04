<!DOCTYPE HTML>
<html>
    <head>
        <title> Create your story </title>
        <meta charset="utf-8">
                <link rel = "stylesheet" type = "text/css" href = "new_story.css">
    </head>
    
    <body><div>
    <h2>Please type in the following filed:</h2>
    <form action="new_story.php" method="POST">
		<input type="hidden" name="token" value="<?php session_start();echo $_SESSION['token'];?>" />
	<p>
        <label>Title:</label><br>
		<textarea name="title" rows=2></textarea>
    </p>
    <p>
		<label>Content:</label><br>
		<textarea name="content" rows=4></textarea>
	</p>
    <p>
		<label>Link(optional):</label><br>
		<textarea name="link" rows=1></textarea>
	</p>
	<p>
		<input type="submit" name = "submit" value = "submit" />
		<input type="submit" name =  "back" value = "back"/>
    </p>
	</form>
	</div>

<?php
// define('__ROOT__', dirname(dirname(__FILE__)));
// require_once(__ROOT__.'/database.php');
require_once('database.php');

if(isset($_POST['token'])&&$_SESSION['token']!=$_POST['token']){
    die("404");
}

if(isset($_POST['back'])){
    header("Location:news.php");
    exit;
}

if(isset($_POST['title'])){
    $story_title=htmlentities($_POST['title']);
    $story_content=htmlentities($_POST['content']);
    $story_link=htmlentities($_POST['link']);
	
	$stmt=$mysqli->prepare("select max(story_id) from story_info");
	if(!$stmt){
		printf("Query failed:%s\n",$mysqli->error);
		exit;
	}
	$stmt->execute();
	$stmt->bind_result($maxID);
    $stmt->fetch();
	$stmt->close();
	$_SESSION['autoID']=$maxID+1;
	
    if($story_title==""){
        echo "The title cannot be empty!";
    }else if($story_link!=""&&!filter_var(filter_var($story_link,FILTER_SANITIZE_URL),FILTER_VALIDATE_URL)){
        echo "The link should be valid!";
    }else{
        $stmt=$mysqli->prepare("insert into story_info (story_id,story_header,story_writer,story_content) values (?,?,?,?)");
        if(!$stmt){
            printf("Query failed:%s\n",$mysqli->error);
            exit;
        }
        $stmt->bind_param('isss',$_SESSION['autoID'],$story_title,$_SESSION['userName'],$story_content);
        $stmt->execute();
        $stmt->close();
		// Get the story_id that user just inserted
		//$lastID=$mysqli->insert_id; 
		
        if($story_link!=""){
            $stmt=$mysqli->prepare("insert into story_link (story_id,story_link) values (?,?)");
            if(!$stmt){
                printf("Query failed:%s\n",$mysqli->error);
                exit;
            }
            $stmt->bind_param('is',$_SESSION['autoID'],$story_link);
            $stmt->execute();
            $stmt->close();
        }else{
			$stmt=$mysqli->prepare("insert into story_link (story_id) values (?)");
            if(!$stmt){
                printf("Query failed:%s\n",$mysqli->error);
                exit;
            }
            $stmt->bind_param('i',$_SESSION['autoID']);
            $stmt->execute();
            $stmt->close();
		}
		
        header("Location:news.php");
        exit;   
    }
}


?>
</body>
</html>