<?php
require_once("DBConn.php");

function printToConsole($string)
{
    echo '<script>console.log("' . $string . '");</script>';
}

$dbConn = new DBConn();
$conn = $dbConn->connect();

if (isset($_POST["editListing"])) {
    $itemID = $_POST['itemID'];
    $itemName = $_POST['itemName'];
    $itemDescription = $_POST['itemDescription'];
    $price = $_POST['price'];
    $username = $dbConn->getUserFromCookie();

    $updateStatus = $dbConn->updateListing($itemID, $itemName, $itemDescription, $price);

    $target_dir = "../resources/items/";
    $target_file = $target_dir . $username . "_" . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $image_chosen = false;
    $image_success = false;

    if (!empty($_FILES["fileToUpload"]["name"][0])) {
        $image_chosen = true;
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
            $dbConn->updateListingPicPath($itemID, $target_file);
            $image_success = true;
        }
    }
    if ($updateStatus == 0 && $image_success) {
        header("Location: ../Front-End/UserListings.php?success&itemid=" . $itemID);
    }
    else if ($updateStatus == 0 && !$image_chosen) {
        header("Location: ../Front-End/UserListings.php?success&itemid=" . $itemID);
    }
    else {
        if ($updateStatus == 0 && $image_chosen && !$image_success) {
            header("Location: ../Front-End/UserListings.php?error=1&itemid=" . $itemID);
        }
    }
}

if (isset($_POST["deleteListing"])) {
    $itemID = $_POST['itemID'];
    $dbConn->deleteListing($itemID);
    header("Location: ../Front-End/UserListings.php");
}

?>