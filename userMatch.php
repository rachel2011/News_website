<?php
    // Prepare connection to database
    // Use a combination of dirname(__FILE__) and subsequent calls to intself until reaching to the home of '/index.php'
    // define('__ROOT__', dirname(dirname(__FILE__)));
    // require_once(__ROOT__.'/database.php');
    require_once('database.php');
    
    // Register
    if(isset($_POST['register'])){
        header("Location:register.php");
        exit;
    }
    // Login as guest user
    if(isset($_POST['guest'])){
        session_start();
        session_destroy();
        session_start();
        $_SESSION['guest']=1;
        $_SESSION['token']=substr(md5(rand()),0,10); // generate a 10-character random string
        header("Location:news.php");
        exit;
    }
    
    
    // Login as registered user
    if(isset($_POST['login'])){
        $IsUserNameNotEmpty = isset($_POST['userName']) && htmlentities($_POST['userName']) != "";
        $IsPwdNotEmpty = isset($_POST['password']) && htmlentities($_POST['password']) != "";                                                                
        
        if ($IsUserNameNotEmpty && $IsPwdNotEmpty) {
            
            $IsUserNameValid = !preg_match('/^[\w_\-]+$/', $_POST['userName']);
            if ($IsUserNameValid) {
                echo "Your username is invalid! Please input another username!";
                exit;
            }
            
            $userName = htmlentities($_POST['userName']);
            $password = htmlentities($_POST['password']);
            $stmt = $mysqli->prepare("select count(*), user_name, password from accounts where user_name = ?");
            
            // Bind the parameter
            $stmt->bind_param('s',$userName);
            $stmt->execute(); // execute query
            
            // Bind the results
            $stmt->bind_result($count, $userNameGet, $pwdHash);
            $stmt->fetch();
            
            // Compare the submitted password to the actual password hash
            // $IsAccountMatched = ();
         
    
            
            if ($count ==1 && crypt($password, $pwdHash) == $pwdHash) {
                
                // Login suceeded!
                session_start();
                echo "Login Successfully!";
                session_destroy();
                session_start();
                $_SESSION['userName'] = $userName;
                $_SESSION['guest']=0;
                $_SESSION['token'] = substr(md5(rand()), 0, 10); // generate a 10-character random string
                
                // Redirect to target page
                header("Location: news.php");
                exit;
            }
            else{
                // Login failed; redirect back to the login page
                // echo("login failed");
                header("Location: login.php");
            }
        }
    }
    
    // Other situations, go back to login.html
    header("Location: login.php");
    exit;
?> 



