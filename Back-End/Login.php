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
            setcookie('usernameCookie', '', time() - 3600, '/'); // empty value and old timestamp
        }
        setcookie('usernameCookie', $user,0,'/');
        header("Location: ../Front-End/Homepage.html");
    }
    else{
        header("Location: ../Front-End/login.php?error=1");
    }
    
    
}
?>