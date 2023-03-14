<?php
require_once("DBConn.php");

$errors = array (
    1 => "Email is already in use. Please use another email and try again.",
    2 => "Image upload failed!"
);

$successMsg = "";
$errorMsg = "";

$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
}

if(isset($_GET['success'])) {
    $successMsg = "Success! Your profile has been updated.";
}

$dbConn = new DBConn();
$conn = $dbConn->connect();

$userInfo = $dbConn->getUserProfileInfo();
$firstName = $userInfo->getFirstName();
$lastName = $userInfo->getLastName();
$email = $userInfo->getEmail();
$zipcode = $userInfo->getZipcode();
$pfpPath = $dbConn->getProfilePicPathExists();
if($pfpPath!=false){
    $pfpPath = $dbConn->getProfilePicPathExists();
}
else{
    $pfpPath = "../resources/pfp/defaultPFP.jpg";
}
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

        <?php echo "<img src=$pfpPath id='profilePic'>" ?>

        <h1>Update Profile</h1>
        <form action="EditProfile.php" method="post" enctype="multipart/form-data">
            <p>
                <b><label for="firstName">First Name:</label></b>
                <input type="text" name="firstName" id="firstName">
            </p>
            <p>
                <b><label for="lastName">Last Name:</label></b>
                <input type="text" name="lastName" id="lastName">
            </p>
            <p>
                <b><label for="emailAddress">Email Address:</label></b>
                <input type="text" name="email" id="emailAddress">
            </p>
            <p>
                <b><label for="zipcode">ZIP Code:</label></b>
                <input type="text" name="zipcode" id="zipcode">
            </p>
            <p>
                <b><label for="fileToUpload">Select image to upload:</label></b>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </p>
            <p><span style="color:red"><?php echo $errorMsg ?></span></p>
            <p><span style="color:green"><?php echo $successMsg ?></span></p>
            <input type="submit" value="Confirm"></input>
        </form>
    </div>
</body>

</html>