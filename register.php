<!DOCTYPE HTML>
<html>
    <head>
        <title>New User Registration</title>
        <link rel = "stylesheet" type = "text/css" href = "register.css">

            </head>
    
    <body>
    
        <h1>Register</h1>
        
        <form action="register.php" method="POST">
            <p>
                <label for = "username">UserName:</label>
                <input type = "text" name="username" id="username" />
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email"/>
            </p>
            <p>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password"/>
            </p>
            <p>
                <input type="submit" name = "register" value = "Register"/>
                <input type="submit" name = "back" value = "Back"/>
        
            </p>
        </form>
        <p>
            <?php
                require_once('database.php');

                // Click Back button
                if(isset($_POST['back'])) {
                    header("Location: login.php");
                    exit;
                    }
                    
                // Click Register button
                if(isset($_POST['register'])){
                    
                    $IsUserNameNotEmpty = isset($_POST['username']) && htmlentities($_POST['username']) != "";
                    $IsPwdNotEmpty = isset($_POST['password']) && htmlentities($_POST['password']) != "";
                    $IsEmailNotEmpty=isset($_POST['email'])&&htmlentities($_POST['email'])!="";
                    if($IsUserNameNotEmpty&&$IsPwdNotEmpty&&$IsEmailNotEmpty) {      
                        
                        $IsUserNameValid = !preg_match('/^[\w_\-]+$/', $_POST['username']);
                        if($IsUserNameValid){
                            $_SESSION['reg_msg']="Your username is invalid! Please input another username!";
                            exit;
                        }
                        
                        // get the user's input username and password and email
                        $userName = htmlentities($_POST['username']);
                        $password = htmlentities($_POST['password']);
                        $email=htmlentities($_POST['email']);
                        // connect to database and check if username exists
                        $stmt = $mysqli->prepare("select count(*) from accounts where user_name = ?");
                
                        // Bind the parameter
                        $stmt->bind_param('s',$userName);
                        $stmt->execute(); // execute query
                        
                        // Bind the results
                        $stmt->bind_result($count);
                        $stmt->fetch();
                        $stmt->close(); // Remember to close!! In case the next time sql doesn't work
                        
                        // The account is available to use when the count is 0
                        if ($count==0) {
                            $pwdHash=crypt($password);
                            $stmt=$mysqli->prepare("insert into accounts (user_name,password,email_address) values (?,?,?)");
                            
                            $stmt->bind_param('sss',$userName,$pwdHash,$email);
                            $stmt->execute();
                            $stmt->close();
                            
                            // Register suceeded! Automatically log in as the registered user
                            
                            session_start();
                            // echo "Registered Successfully!";
                            session_destroy();
                            
                            // Set the global variable userName and start a new session
                            session_start();
                            $_SESSION['userName'] = $userName;
                            // Generate a 10-character random string
                            $_SESSION['token'] = substr(md5(rand()), 0, 10);
                            $_SESSION['guest']=0;
                            // Redirect to target page
                            header("Location: news.php");
                            exit;
                        }
                        else{
                            // Registered failed; redirect back to the register page
                            $error_msg="The username is already taken! Please choose another! Wait this page to refresh in 3s...";
                            echo ($error_msg);
                            // Pause for 3s to display error message 
                            header("refresh:3; url=register.php" );
                            exit;
                        }
                    }
                    
                }
                exit;     
            ?>
        </p>      
    </body>
</html>