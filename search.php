<!DOCTYPE HTML>
<html>
    <head>
        <title>Search</title>
    </head>
	
    <body>
<?php
session_start();
require 'database.php';
//get target in search function
$target = "%".$_POST['target']."%";
printf('Search Result');

//search for users
if($_POST['searchRange'] == 'user'){
	$stmt = $mysqli->prepare("select user_name,email_address from accounts where user_name like ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('s', $target);
	$stmt->execute();
	$stmt->bind_result($Resultusername,$Resultemailaddress);
	echo "Search result: \n";
	while($stmt->fetch()){
		printf("<ul>\n
			<li>%s</li>
            <li>%s</li>
		</ul>\n\n",
         htmlspecialchars($Resultusername),
         htmlspecialchars($Resultemailaddress));
	}
	$stmt->close();
    printf('<a href="news.php">Back</a>');
//search for stories
}else{
	$stmt = $mysqli->prepare("select story_header, story_content, story_writer, story_time from story_info 
		where story_header like ? or story_content like ? or story_writer like ?");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt->bind_param('sss', $target, $target, $target);            
	$stmt->execute();     
	$stmt->bind_result($showTitle, $showContent, $showWriter, $showTime);
	echo "Search result: \n";
	while($stmt->fetch()){
		printf("<ul>\n
			\t<li>Title: %s</li>
			<li>Content: %s</li>
			<li>Author: %s</li>
			<li>Time: %s</li>\n
		</ul>\n\n",
		htmlspecialchars($showTitle),
		htmlspecialchars($showContent),
		htmlspecialchars($showWriter),
		htmlspecialchars($showTime)
		);
	}
	$stmt->close();
    printf('<a href="news.php">Back</a>');
}
?>
	</body>
</html>