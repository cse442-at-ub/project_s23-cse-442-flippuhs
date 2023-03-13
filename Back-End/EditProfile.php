<?php
require("DBConn.php");

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$zipcode = $_POST['zipcode'];

$dbConn = new DBConn();
$conn = $dbConn->connect();

$result = $dbConn->updateUserProfileInfo($firstName, $lastName, $email, $zipcode);

if($result == 0) {
    header("Location: ProfilePage.php?success");
}
else{
    header("Location: ProfilePage.php?error=1");
}

