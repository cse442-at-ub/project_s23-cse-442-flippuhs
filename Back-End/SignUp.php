<?php
require_once('DBConn.php');

if (isset($_POST['userData'])) {
    $dbConn = new DBConn();
    $conn = $dbConn->connect();

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $reenter = $_POST['repassword'];
    $zipcode = $_POST['zipcode'];

    if($pass == $reenter){
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $dbConn->insertUser($user,$first_name,$last_name,$email,$hash,$zipcode);
        header("Location: ../Front-End/login.php");
    }
    else{
        echo "Passwords don't match";
    }
}
?>