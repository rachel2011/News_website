<!DOCTYPE HTML>
<html>
    <head>
        <title>Profile</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "profile.css">
    </head>
    <body>
		<div class="container">
    <?php
    session_start();
    if(isset($_SESSION['token'])){
        $token=$_SESSION['token'];
        }
    printf("<div id='content'>");
        //update personal profile
        printf("<div id='update'>");
        printf('<form action="update.php" method="POST">
               <h4>Update profile</h4>
               <input type="hidden" name="form_type" value="updateUser"/>
               <input type="hidden" name="token" value=%s />
        
               <label for="updatePwd">New Password</label>
               <input type="password" name="updatePwd" id="updatePwd" placeholder="New Password">
               <p>
                <input type="submit" name = "update" value = "Update"/>
                <input type="reset" value="Reset"/>
                </p>
                </form>', $_SESSION['token']);
        printf("</div>");  
//show profile information
if(isset($_SESSION['userName'])){
printf("<h2>Hello %s!\n</h2>", htmlentities($_SESSION['userName']));
}
require 'database.php';
//echo "</ul>\n\n";

$stmt = $mysqli->prepare("select user_name, email_address from accounts where user_name=?");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
    }
    $stmt->bind_param('s', $_SESSION['userName']);            
    $stmt->execute();     
    $stmt->bind_result($userName, $email_address);
                 
    echo "<ul>\n";
    while($stmt->fetch()){
        printf("\t<li >Your username: %s</li>
               <li>Your email: %s</li>\n",
               htmlspecialchars($userName),
               htmlspecialchars($email_address)
               );
        }
        echo "</ul>\n\n";
        $stmt->close();
        
     
     //list stories
     $username = $_SESSION['userName'];
     
     $stmt=$mysqli->prepare("select story_header, story_id, story_time, story_content from story_info where story_writer=? ");
                if(!$stmt){
                    printf("Query stories failed: %s\n",$mysqli->connect-error);
                    exit;
                }
                $stmt->bind_param('s',$username);
                $stmt->execute();
                $stmt->bind_result($storyHeader,$storyID,$storyTime,$story_content);
                while($stmt->fetch()){
                    // list all the stories 
                    printf("<ul>
                           <li>Title:%s</li>
                            <li>Time:%s</li>
                            </ul>
                            <p>Content:%s</p>",
                            htmlspecialchars($storyHeader),
                            htmlspecialchars($storyTime),
                            htmlspecialchars($story_content));
                    printf("<form action='action.php' method='POST'>
                                <input type='hidden' name='storyID' value=%s>
                                <input type='hidden' name='token' value=%s>
                                <input type='submit' name='ReadStory' value='Read'>",
                                htmlspecialchars($storyID),
                                htmlspecialchars($token)); // read details, CSRF detection
                    $IsStoryOfUser=isset($_SESSION['userName']) && htmlspecialchars($username==$_SESSION['userName']);
                    if($IsStoryOfUser){
                        printf("<input type='submit' name='EditStory' value='Edit'>
                                <input type='submit' name='DeleteStory' value='Delete'>");
                    }
                    echo "</form>";
                    }// all the stories are listed
                    
                    $stmt->close();
                
                    printf("</div>");// story list ends
                      
?>
		</div>
		<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
		<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>