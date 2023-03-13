<?php
require_once("DBConn.php");

$dbConn = new DBConn();
$conn = $dbConn->connect();

$firstName = $dbConn->getUserProfileInfo()->getFirstName();
$lastName = $dbConn->getUserProfileInfo()->getLastName();
$email = $dbConn->getUserProfileInfo()->getEmail();
$zipcode = $dbConn->getUserProfileInfo()->getZipcode();
?>

<html lang="en">

<head>
    <link rel="stylesheet" href="../Front-End/style.css">
    <title>My Profile</title>
</head>

<body>
    <div id="profileForm">
        <h1>Profile Information</h1>
        <b><p>First Name: </b><?php echo $firstName ?></p>
        <b><p>Last Name: </b><?php echo $lastName ?></p>
        <b><p>Email: </b><?php echo $email ?></p>
        <b><p>ZIP Code: </b><?php echo $zipcode ?></p>
    </div>
</body>

</html>