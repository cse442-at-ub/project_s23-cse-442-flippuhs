<?php
$errors = array (
    1 => "There was an error with your image, try again."
);

$successMsg = "";
$errorMsg = "";

$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
}

if(isset($_GET['success'])) {
    $successMsg = "Success! Your listing has been created.";
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
<?php include 'navbar.php';?>
    <form id="login-container" action="../Back-End/NewListing.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label class="signuptext" for="itemName">Item Name:</label>
            <input type="text" name="item_name" id="itemName" required="required">
        </div>
        <div class="form-group">
            <label class="signuptext" for="itemDesc">Item Description:</label>
            <input type="text" name="item_desc" id="itemDesc" required="required">
        </div>
        <div class="form-group">
            <label class="signuptext"for="itemPrice">Item Price $:</label>
            <input type="number" name="item_price" id="itemPrice"maxlength="12" required="required" step="1"
                placeholder="0"/>
        </div>
        <div class="form-group">
            <label class="signuptext" for="imageToUpload">Select image to upload:</label>
            <input class="fileinput" type="file" name="imageToUpload" onchange="VerifyUploadSizeIsOK()" id="imageToUpload" required="required">
        </div>
        <div class="form-group">
            <input class="navbarbutton2" style="width:100%; margin: 10px auto" type="submit" name="createListing" value="Confirm"></input>
        </div>
        <p><span style="color:red"><?php echo $errorMsg ?></span></p>
        <p><span style="color:green"><?php echo $successMsg ?></span></p>
    </form>
    <script type="text/javascript">
        function VerifyUploadSizeIsOK()
        {
        /* Attached file size check. Will Bontrager Software LLC, https://www.willmaster.com */
        var UploadFieldID = "imageToUpload";
        var MaxSizeInBytes = 2097152;
        var fld = document.getElementById(UploadFieldID);
        if( fld.files && fld.files.length == 1 && fld.files[0].size > MaxSizeInBytes )
        {
            alert("The file size must be no more than " + parseInt(MaxSizeInBytes/1024/1024) + "MB");
            window.location = '../Front-End/CreateListing.php'
            return false;
        }
        return true;
        }
    </script>
</body>
</html>