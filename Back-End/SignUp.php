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
    $invalid = true;

    //Check if email already exists
    $exists = $dbConn->getEmailExists($email);
    if ($exists) {
        header("Location: ../Front-End/signup.php?error=6");
        $invalid = false;
    }

    //Check if username already in use
    $exists =  $dbConn->getUserExists($user);
    if ($exists) {
        header("Location: ../Front-End/signup.php?error=7");
        $invalid = false;
    }

    //Password Checks
    //8 Characters
    if(strlen($pass) < 8){
        header("Location: ../Front-End/signup.php?error=2");
        $invalid = false;
    }
    //At least one uppercase character
    if(!preg_match('/[A-Z]/', $pass)){
        header("Location: ../Front-End/signup.php?error=8");
        $invalid = false;
    }

    //At least one digit
    if(!preg_match('~[0-9]+~', $pass)){
        header("Location: ../Front-End/signup.php?error=4");
        $invalid = false;
    }

    //Special Characters
    if(!preg_match('/[^a-zA-Z\d]/', $pass)){
        header("Location: ../Front-End/signup.php?error=5");
        $invalid = false;
    }
    
    //Passwords don't match
    if($pass != $reenter){
        header("Location: ../Front-End/signup.php?error=1");
    }

    if($pass == $reenter and $invalid){
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $dbConn->insertUser($user,$first_name,$last_name,$email,$hash,$zipcode);
        header("Location: ../Front-End/login.php");
    }
    
}
?>