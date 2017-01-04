<!DOCTYPE html>
<html>
    <head>
        <title>
            Read Story
        </title>
        <meta charset="utf-8">
        <link rel = "stylesheet" type = "text/css" href = "readStory.css">
    </head>
    <body>
    <?php
        require_once('database.php');
        session_start();
        $_SESSION['WhereAmI']=3;
        
        $stmt=$mysqli->prepare("select story_header,story_writer,story_time,story_content from story_info where story_id=?");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
        
		$stmt->bind_param('i',$_SESSION['storyID']);
		$stmt->execute();
        $stmt->bind_result($storyHeader,$storyWriter,$storyTime,$storyContent);
        $stmt->fetch();
        
        // List the all the story info
		printf("<div id='content'>");
        printf("\n
			   <h3>Title:%s</h3>
			   <ul>
                <li>Author:%s</li>
                <li>Time:%s</li>
				</ul>
                <p>Content:%s</p>",
                htmlspecialchars($storyHeader),
                htmlspecialchars($storyWriter),
                htmlspecialchars($storyTime),
                htmlspecialchars($storyContent));
      
        $stmt->close();    
            
        // Display the story link
        $stmt=$mysqli->prepare("select story_link from story_link where story_id=?");
        if(!$stmt){
            printf("Query Failed: %s\n",$mysqli->error);
            exit;
        }
        $stmt->bind_param('i',$_SESSION['storyID']);
        $stmt->execute();
        $stmt->bind_result($story_link);
        $stmt->fetch();
        $stmt->close();
        printf("<a href='%s'>For more details, please check the link: %s</a><br>",
               htmlspecialchars($story_link),
               htmlspecialchars($story_link));
        
        // Display all the comments order by time desc
        $stmt=$mysqli->prepare("select comment_id,comment_content,comment_writer,comment_time from comment_info where story_id=? order by comment_time desc");
		if(!$stmt){
			printf("Query Prep Failed: %s\n", $mysqli->error);
			exit;
		}
        
		$stmt->bind_param('i',$_SESSION['storyID']);
		$stmt->execute();
        $stmt->bind_result($commentID,$commentContent,$commentWriter,$commentTime);
		printf("<h3>Comment:</h3>");
        while($stmt->fetch()){
            // List all the comments 
            printf("\n
				   <li>%s</li>
                    <li>Time:%s</li>
                    <p>%s</p>",
                    htmlspecialchars($commentWriter),
                    htmlspecialchars($commentTime),
                    htmlspecialchars($commentContent));
            
            $IsCommentOfUser=isset($_SESSION['userName'])&&htmlspecialchars($commentWriter==$_SESSION['userName']);
            if($IsCommentOfUser){
                printf("<form action='action.php' method='POST'>
                        <input type='hidden' name='storyID' value='%s'>
                        <input type='hidden' name='commentID' value=%s>
                        <input type='hidden' name='token' value='%s'>
                        <input type='submit' name='EditComment' value='EditComment'>
                        <input type='submit' name='DeleteComment' value='DeleteComment'>",
                        htmlspecialchars($_SESSION['storyID']),
                        htmlspecialchars($commentID), 
                        htmlspecialchars($_SESSION['token'])); // read details, CSRF detection
            
            }
            
            echo "</form>";   
        }// all the comments are listed
        
        // Create a comment to the news only accessed by registered users
        
        if($_SESSION['guest']!=1){
        printf("<form action='action.php' method='POST'>
                    <input type='hidden' name='token' value='%s'>
                    <input type='hidden' name='storyID' value='%s'>
                    <h3>
                        <label>Your comment:</label><br>
                        <textarea name='commentContent' rows=5></textarea>
                    </h3>
                    <p>
                        <input type='submit' name='CommentStory' value='Comment'>
                        <input type='reset' value='Reset'>
                        <input type='submit' name='Back2news' value='Back'>
                    </p>
                </form>",
                htmlspecialchars($_SESSION['token']),
                htmlspecialchars($_SESSION['storyID']));
		//printf("</div>");
        }
    ?>    
     
    </div></body>    
</html>