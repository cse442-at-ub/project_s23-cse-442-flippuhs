<?php
require_once('DBConn.php');


if (isset($_POST['login'])) {
    $dbConn = new DBConn();
    $conn = $dbConn->connect();

    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (password_verify($pass, $dbConn->getUserPassword($user))){
        if (isset($_COOKIE['usernameCookie'])) {
            unset($_COOKIE['usernameCookie']);
            setcookie('usernameCookie', '', time() - 3600, '/',null,true,true); // empty value and old timestamp
        }
        $hash = bin2hex(random_bytes(50));
        setcookie('usernameCookie', $hash,0,'/',null,true,true);
        $dbConn->insertUsernameHash($user,$hash);
        header("Location: ../Front-End/Homepage.php");
    }
    else{
        header("Location: ../Front-End/login.php?error=1");
    }
    
    
}

if(isset($_POST["logout"])){
    //clear cookie
    if (isset($_COOKIE['usernameCookie'])) {
        unset($_COOKIE['usernameCookie']);
        setcookie('usernameCookie', '', time() - 3600, '/',null,true,true); // empty value and old timestamp
    }
    header("Location: ../Front-End/login.php");
}
?>