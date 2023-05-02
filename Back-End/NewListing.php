<?php
require_once("DBConn.php");
require_once("CSRF.php");

function printToConsole($string)
{
    echo '<script>console.log("' . $string . '");</script>';
}

if (!CSRF::verifyToken($_POST['csrf_token'])) {
    header("Location: ../Front-End/login.php");
}
else{
    if (isset($_POST['createListing'])) {
        $dbConn = new DBConn();
        $conn = $dbConn->connect();

        $username = $dbConn->getUserFromCookie();

        $item_name = $_POST['item_name'];
        $item_desc = $_POST['item_desc'];
        $item_price = $_POST['item_price'];
        $selling_method= $_POST['selling_method'];

        if ($item_price < 0 || $item_price > 2147483647) {
            header("Location: ../Front-End/CreateListing.php?error=2");
        }
        else{
            $target_dir = "../resources/items/";
            $target_file = $target_dir . $username . "_" . basename($_FILES["imageToUpload"]["name"]);
            $uploadOk = 1;
            $image_chosen = false;
            $image_success = false;

            if (!empty($_FILES["imageToUpload"]["name"][0])) {
                $image_chosen = true;
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["imageToUpload"]["tmp_name"]);
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
                    if (move_uploaded_file($_FILES["imageToUpload"]["tmp_name"], $target_file)) {
                        printToConsole("The file " . htmlspecialchars(basename($_FILES["imageToUpload"]["name"])) . " has been uploaded.");
                        $dbConn->insertNewListing($item_name, $item_desc, $item_price, $target_file, $username, $selling_method);
                        $image_success = true;
                        printToConsole("New Listing created");
                    } else {
                        printToConsole("Sorry, there was an error creating your listing.");
                    }
                }
            }

            if ($image_success) {
                header("Location: ../Front-End/CreateListing.php?success");
            }
            else {
                header("Location: ../Front-End/CreateListing.php?error=1");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../Front-End/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
</body>

</html>