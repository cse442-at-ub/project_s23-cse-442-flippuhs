<?php
require_once('DBConn.php');


if (isset($_POST['login'])) {
    $dbConn = new DBConn();
    $conn = $dbConn->connect();

    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (password_verify($pass, $dbConn->getUserPassword($user))){
        setcookie('usernameCookie', $user);
        // header("Location: ../Front-End/Homepage.html");
        header("Location: ../Back-End/ProfilePage.php");
    }
    else{
        header("Location: ../Front-End/login.php?error=1");
    }
    
    
}
?>