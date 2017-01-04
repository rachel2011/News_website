<!DOCTYPE HTML>
<html>
    <head>
        <title> Dive </title>
        <meta charset = "UTF-8">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "news.css">
    </head>
    
    <body>
        <!-- navigation bar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div id="navig" class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">DIVE</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="index.html">Home</a></li>
            <?php
                session_start();

                if(isset($_SESSION['token'])){
                    $token=$_SESSION['token'];

                }
                
                if(isset($_SESSION['userName'])){
                    printf("<li><a href='new_story.php'>Post</a></li>\n");
                    printf("<li><a href='profile.php'>%s's Page</a></li>\n",htmlspecialchars($_SESSION['userName']));
                    printf("<li><a href='logout.php'>Log out</a></li\n");
                }
                else if(isset($_SESSION['guest'])&&($_SESSION['guest']!=0)){
                    printf("<li><a href='register.php'>Register now!</a></li>\n");
                    printf("<li><a href='login.php'>Log in</a></li>\n");
                }// navigation section ends

            ?>
          </ul>
          
          <div id="search" class="btn-group pull-right">
          	<form action="search.php" class="navbar-search offset1" method="post">
             <input type="text" name ="target" class="search-query" placeholder="Search">
              <select name="searchRange">
                 <option value="user">User</option>
                 <option value="story">Story</option>
              </select>
              <button type="submit" class="btn btn-default">Search</button>
            </form>
          </div>
        </div>
      </div>
    </nav>
    <div id="wrapper">
    <!--  contents -->
    <div id='content'>
        <div id="logo"><img src="divelogo.jpg" alt="logo" width="120" height="120"/></div>
        <div id='title0'>DIVE</div><br/>
        <div id='title1'>Explore the world uniquely</div><br/>
   
        <?php
                  
                require_once('database.php');
                $_SESSION['WhereAmI']=0;

                // To debug w3c error: Attribute value missing..
                // echo $_SESSION['token'];
                // echo htmlspecialchars($_SESSION['token']);
                
                
                // list stories
                $stmt=$mysqli->prepare("select story_writer, story_header, story_id, story_time from story_info order by story_time desc ");
                if(!$stmt){
                    printf("Query stories failed: %s\n",$mysqli->connect-errno);
                    exit;
                }
                $stmt->execute();
                $stmt->bind_result($storyWriter,$storyHeader,$storyID,$storyTime);
                printf("<div id='content_table'>
                            <div id='maintable' class='css_tr'>
                            <div class='css_td'>WHAT IS HAPPENNING?</div>
                            <div class='css_td'>AUTHOR</div>
                            <div class='css_td'>PUBLISH TIME</div>
                            <div class='css_td'>OPERATION</div>
                        </div>");
                while($stmt->fetch()){
                    // list all the stories



                    printf("<div class='css_tr'>
                                <div class='css_td'>%s</div>
                                <div class='css_td'>%s</div>
                                <div class='css_td'>%s</div>
                            ",
                            htmlspecialchars($storyHeader),
                            htmlspecialchars($storyWriter),
                            htmlspecialchars($storyTime));
                    // Don't forget to add '' while using %s
                    printf("<div class='css_td'>
                                <form action='action.php' method='POST'>
                                <input type='hidden' name='storyID' value=%s>
                                <input type='hidden' name='token' value='%s'>
                                <input type='submit' name='ReadStory' value='Read'>
                            

                                ",
                                htmlspecialchars($storyID),
                                htmlspecialchars($_SESSION['token'])); // read details, CSRF detection
                    $IsStoryOfUser=isset($_SESSION['userName']) && htmlspecialchars($storyWriter==$_SESSION['userName']);
                    if($IsStoryOfUser){
                        printf("<input type='submit' name='EditStory' value='Edit'>
                                <input type='submit' name='DeleteStory' value='Delete'>");
                    }
                     echo "</form>";
                        echo "</div>
                        </div>";
   
                }// all the stories are listed
                echo "</div>";
                $stmt->close();
            ?>
    </div>
  
            
    </div>
        
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>    
   
    </body>
</html>