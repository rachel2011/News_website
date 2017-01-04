<?php
    // define('__ROOT__', dirname(dirname(__FILE__)));
    // require_once(__ROOT__.'/database.php');
    require_once('database.php');
    session_start();
    // Check
    if($_SESSION['token']!== $_POST['token']){
        die("404");
    }
    
    // Store the storyID from the form
    $_SESSION['storyID']=htmlspecialchars($_POST['storyID']);
   
    if(isset($_POST['ReadStory'])){ // Read a story
        header("Location: readStory.php");
        exit;
    }else if(isset($_POST['EditStory'])){ // Edit a story
        header("Location:editStory.php");
        exit;
    }else if(isset($_POST['DeleteStory'])){ // Delete a story
        
        // First delete its comments by its story_id
        $stmt=$mysqli->prepare("delete from comment_info where story_id=?");
		if(!$stmt){
			printf("Query Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i',$_SESSION['storyID']);
		$stmt->execute();
        $stmt->close();
        
        // Then delete its story_link
        $stmt=$mysqli->prepare("delete from story_link where story_id=?");
		if(!$stmt){
			printf("Query Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i',$_SESSION['storyID']);
		$stmt->execute();
        $stmt->close();
        
        // Finally delete the story by its story_id
        // TODO: 
        $stmt=$mysqli->prepare("delete from story_info where story_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i',$_SESSION['storyID']);
		$stmt->execute();
        $stmt->close();
        
        // Delete sucessfully and go back to where I come from: 1:profile.php 0:news.php
        if(isset($_SESSION['WhereAmI'])&&$_SESSION['WhereAmI']==1){
            $_SESSION['WhereAmI']=0;
            header("Location:profile.php");
            exit;
        }else{
            header("Location:news.php");
            exit;
        }
    }else if(isset($_POST['CommentStory'])){ // Comment a story
        // Get the max id in the comment_info database
        $stmt=$mysqli->prepare("select max(comment_id) from comment_info");
        if(!$stmt){
            printf("Query failed:%s\n",$mysqli->error);
            exit;
        }
        $stmt->execute();
        $stmt->bind_result($maxCommentID);
        $stmt->fetch();
        $stmt->close();
        $_SESSION['autoCommentID']=$maxCommentID+1;

        $stmt=$mysqli->prepare("insert into comment_info (comment_id,comment_writer,story_id,comment_content) values (?,?,?,?)");
                            
        $stmt->bind_param('isis',$_SESSION['autoCommentID'],$_SESSION['userName'],$_SESSION['storyID'],$_POST['commentContent']);
        $stmt->execute();
        $stmt->close();
        
        // After commenting, go back to readStory.php
        header("Location:readStory.php");     
        exit;
    }else if(isset($_POST['EditComment'])){ // Edit a comment
        $_SESSION['commentID']=$_POST['commentID'];
        header("Location:editComment.php");
        exit;
    }else if(isset($_POST['DeleteComment'])){  // Delete a comment
        
        // Delete a comment by its comment_id
        // TODO: 
        $stmt=$mysqli->prepare("delete from comment_info where comment_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
		$stmt->bind_param('i',$_POST['commentID']);
		$stmt->execute();
        $stmt->close();
        
        // Delete comment sucessfully and go back to where I come from: 1:profile.php 3:readStory.php
        if(isset($_SESSION['WhereAmI'])&&$_SESSION['WhereAmI']==3){
            //$_SESSION['WhereAmI']=0;
            header("Location:readStory.php");
            exit;
        }else {
            header("Location:profile.php");
            exit;
        }
        
    }else if(isset($_POST['Back2news'])){
        header("Location:news.php");
        exit;    
    }else{
        header("Location:news.php");
        exit;
    }
    
    
    
    
?>