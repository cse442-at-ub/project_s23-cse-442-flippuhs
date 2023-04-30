<?php
require_once("DBConn.php");
require_once("CSRF.php");

$token = CSRF::generateToken();

if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

session_start();
unset($_SESSION['searchValue']);
unset($_SESSION["sortValue"]);

$errors = array (
    1 => "Email is already in use. Please use another email and try again.",
    2 => "Image upload failed!",
    3 => "Invalid zipcode entered."
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
?>

<html lang="en">

<head>
    <link rel="stylesheet" href="../Front-End/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>My Profile</title>
</head>

<body>

    <?php include '../Front-End/navbar.php';?>

    <form id="login-container" action="EditProfile.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <div style="text-align: center"><img id="pfp" src="<?php echo htmlspecialchars($pfpPath)?>"/></div>
            <h1 id="logo">Profile Information</h1>
        </div>
        <div class="form-group">
            <label class="signuptext" for="firstName">First Name:</label>
            <input type="text" name="firstName" id="firstName" value="<?php echo htmlspecialchars($firstName)?>">
        </div>
        <div class="form-group">
            <label class="signuptext" for="lastName">Last Name:</label>
            <input type="text" name="lastName" id="lastName" value="<?php echo htmlspecialchars($lastName)?>">
        </div>
        <div class="form-group">
            <label class="signuptext" for="emailAddress">Email Address:</label></b>
            <input type="text" name="email" id="emailAddress" placeholder="<?php echo htmlspecialchars($email)?>">
        </div>
        <div class="form-group">
            <label class="signuptext" for="zipcode">ZIP Code:</label>
            <input type="text" name="zipcode" id="zipcode" value="<?php echo htmlspecialchars($zipcode)?>">
        </div>
        <div class="form-group">
            <label class="signuptext" for="fileToUpload">Select image to upload:</label>
            <input class="fileinput" type="file" name="fileToUpload" onchange="VerifyUploadSizeIsOK()" id="fileToUpload">
            <label class="signuptext">Maximum upload file size: 2 MB.</label>
        </div>
        <div class="form-group">
            <p><span style="color:red"><?php echo $errorMsg ?></span></p>
            <p><span style="color:green"><?php echo $successMsg ?></span></p>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
        <div class="form-group">
            <input class="navbarbutton2" style="width:100%; margin: 10px auto" type="submit" name="editProfile" value="Confirm"></input>
            <input class="navbarbutton1" style="width:100%; margin: 10px auto" type="submit" name="deleteAccount" value="Delete Account"/>
        </div>
    </form>

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
            window.location = '../Back-End/ProfilePage.php'
            return false;
        }
        return true;
        }
    </script>

</body>

</html>