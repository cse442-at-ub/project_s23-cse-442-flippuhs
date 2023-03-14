<?php
require_once("DBConn.php");

function printToConsole($string) {
    echo '<script>console.log("' . $string . '");</script>';
}

$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$zipcode = $_POST['zipcode'];

$dbConn = new DBConn();
$conn = $dbConn->connect();

$updateStatus = $dbConn->updateUserProfileInfo($firstName, $lastName, $email, $zipcode);

$username = "johndoe"; //Temporary dummy value
$target_dir = "../resources/pfp/";
$target_file = $target_dir . $username . "_" . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$image_success = false;

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check !== false) {
    printToConsole("File is an image - " . $check["mime"] . ".");
    $uploadOk = 1;
} else {
    printToConsole("File is not an image.");
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    printToConsole("Sorry, your file was not uploaded.");
    // if everything is ok, try to upload file
} else {
    printToConsole($target_file);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        printToConsole("The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.");
        //Check if user already has PFP uploaded so UPDATE instead of INSERT
        $result = $dbConn->getProfilePicPathExists();
        if($result!=false){
            $dbConn->updateUserProfilePicPath($username,$target_file);
        }
        else {
            $dbConn->insertUserProfilePicPath($username,$target_file);
        }
        $image_success = true;
        printToConsole("Profile Picture Path Updated");
    } else {
        printToConsole("Sorry, there was an error uploading your file.");
    }
}

if($updateStatus == 0 && $image_success) {
    header("Location: ProfilePage.php?success");
}
else{
    if($updateStatus != 0 && $image_success){
        header("Location: ProfilePage.php?error=1");
    }
    if($updateStatus == 0 && !$image_success){
        header("Location: ProfilePage.php?error=2");
    }
}


?>