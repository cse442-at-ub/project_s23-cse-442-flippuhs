<?php 
require_once('../Back-End/DBConn.php');

$dbConn = new DBConn();
$conn = $dbConn->connect();

//DELETE
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

$seller = $_GET['username'];

if($dbConn->getUserInfoByUsername($seller)!=false){
    $userInfo = $dbConn->getUserInfoByUsername($seller);
    $firstName = $userInfo->getFirstName();
    $lastName = $userInfo->getLastName();
    $email = $userInfo->getEmail();
    $zipcode = $userInfo->getZipcode();
    $pfpPath = "../resources/pfp/defaultPFP.jpg";
}

if($dbConn->getNumListingsByUsername($seller) != false){
    $numListings = $dbConn->getNumListingsByUsername($seller);
}
else{ 
    $numListings = 0;
}

?>

<html>
<head>
    <link rel="stylesheet" href="../Front-End/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>My Profile</title>
</head>

<body>
<?php include '../Front-End/navbar.php';?>
<?php $seller = $_GET['username']; ?>

<h1></h1>
<div style="text-align: center"><img id="pfp" src="<?php echo htmlspecialchars($pfpPath)?>"/></div>
<h1 style="text-align: center" id="logo"><?php echo htmlspecialchars($seller)?></h1>
<h2 style="text-align: center" id="logo">Name: <?php echo htmlspecialchars($firstName)?> <?php echo htmlspecialchars($lastName)?></h2>
<h2 style="text-align: center" id="logo">Email: <?php echo htmlspecialchars($email)?></h2>
<h2 style="text-align: center" id="logo">Zipcode: <?php echo htmlspecialchars($zipcode)?></h2>
<h2 style="text-align: center" id="logo">Number of Listings: <?php echo htmlspecialchars($numListings)?></h2>

</body>
</html>
