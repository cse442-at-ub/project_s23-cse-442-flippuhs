<?php

require_once('../Back-End/DBConn.php');

if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

$errors = array (
    1 => "Image upload failed!"
);

$errorMsg = "";
$itemErrorId = isset($_GET['itemid']) ? (int)$_GET['itemid'] : null;
$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($itemErrorId != null && $errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
}

$successMsg = "";
$itemErrorId = isset($_GET['itemid']) ? (int)$_GET['itemid'] : null;
if($itemErrorId != null && isset($_GET['success'])) {
    $successMsg = "Success! Your listing has been updated.";
}

$dbConn = new DBConn();
$conn = $dbConn->connect();

if($dbConn->getUserProfileInfo()!=false){
    //check that usernameCookie exists or else redirect to login page
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
}
else{
    header("Location: ../Front-End/login.php");
}

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$no_of_records_per_page = 2;
$offset = ($pageno-1) * $no_of_records_per_page; 

$total_rows = $dbConn->getNumUserListings();
$total_pages = ceil($total_rows / $no_of_records_per_page);

$resData = $dbConn->getUserListings($offset,$no_of_records_per_page);

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
<?php include 'navbar.php';?>
<ul class="pagination">
    <li><a class='logoutbutton' href="?pageno=1">First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a class='logoutbutton' href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a class='logoutbutton' href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a class='logoutbutton' href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
</ul>
<table style='width:100%'>
	<?php if($resData!=false)while ($row = $resData->fetch_assoc()): ?>
	<tr>
		<td>
        <?php echo "<form id='login-container' action='../Back-End/EditListing.php' method='post' enctype='multipart/form-data'>"; ?>
            <?php echo "<img src=" . htmlspecialchars($row['imagepath']) . " class='listing'>".'<br>'; ?>
            <?php echo "<div class='form-group'>"; ?>
                <?php echo "<label class='signuptext' for='itemName'>Item Name:</label>"; ?>
                <?php echo "<input type='text' name='itemName' id='itemName' value=" . htmlspecialchars($row['itemname']) . ">"; ?>

                <?php echo "<label class='signuptext' for='itemDescription'>Item Description:</label>"; ?>
                <?php echo "<input type='text' name='itemDescription' id='itemDescription' value=" . htmlspecialchars($row['itemdesc']) . ">"; ?>

                <?php echo "<label class='signuptext' for='price'>Price:</label>"; ?>
                <?php echo "<input type='text' name='price' id='price' value=" . htmlspecialchars($row['price']) . ">"; ?>

                <?php echo "<label class='signuptext' for='fileToUpload'>Select image to upload:</label>"; ?>
                <?php echo "<input class='fileinput' type='file' name='fileToUpload' onchange='VerifyUploadSizeIsOK()' id='fileToUpload'>"; ?>
                <?php echo "<label class='signuptext'>Maximum upload file size: 2 MB.</label>"; ?>

                <?php echo "<input type='hidden' name='itemID' value=$row[itemid]>"; ?>

                <?php
                    if ($itemErrorId == $row['itemid']) {
                        echo "<p><span style='color:red'>$errorMsg</span></p>"; 
                    }
                ?>
                <?php
                    if($itemErrorId == $row['itemid']) {
                        echo "<p><span style='color:green'>$successMsg</span></p>"; 
                    }
                ?>

                <?php echo "<input class='navbarbutton2' style='width:100%; margin: 10px auto' type='submit' name='editListing' value='Update'></input>"; ?>
                <?php echo "<input class='navbarbutton1' style='width:100%; margin: 10px auto' type='submit' name='deleteListing' value='Delete Listing'/>"; ?>
            <?php echo "</div>" ?>
        <?php echo "</form>"; ?>
        </td>
	</tr>
	<?php endwhile; ?>
</table>

<script type="text/javascript">
        function VerifyUploadSizeIsOK()
        {
        /* Attached file size check. Will Bontrager Software LLC, https://www.willmaster.com */
        var UploadFieldID = "fileToUpload";
        var MaxSizeInBytes = 2097152;
        var fld = document.getElementById(UploadFieldID);
        if( fld.files && fld.files.length == 1 && fld.files[0].size > MaxSizeInBytes )
        {
            alert("The file size must be no more than " + parseInt(MaxSizeInBytes/1024/1024) + "MB");
            window.location = '../Front-End/UserListings.php'
            return false;
        }
        return true;
        }
</script>

</body>
</html>