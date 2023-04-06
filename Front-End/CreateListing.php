<?php
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
            <input class="fileinput" type="file" name="imageToUpload" id="imageToUpload" required="required">
        </div>
        <div class="form-group">
            <input class="navbarbutton2" style="width:100%; margin: 10px auto" type="submit" name="createListing" value="Confirm"></input>
        </div>
    </form>
</body>
</html>