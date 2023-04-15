<?php 
require_once('../Back-End/DBConn.php');

$dbConn = new DBConn();
$conn = $dbConn->connect();


$seller = $_GET['sellername'];

if($dbConn->getUserInfoByUsername($seller)!=false){
    $userInfo = $dbConn->getUserInfoByUsername($seller);
    $first = $userInfo->getFirstName();
    $last = $userInfo->getLastName();
    $email1 = $userInfo->getEmail();
    $zip = $userInfo->getZipcode();
    $pfp = $dbconn->getProfilePicByUsername($seller);
    if($pfp!=false){
        $pfp = $dbConn->getProfilePicByUsername($seller);
    }
    else{
        $pfp = "../resources/pfp/defaultPFP.jpg";
    }
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

<h1></h1>
<div style="text-align: center"><img id="pfp" src="<?php echo htmlspecialchars($pfp)?>"/></div>
<h1 style="text-align: center" id="logo"><?php echo htmlspecialchars($seller)?></h1>
<h2 style="text-align: center" id="logo">Name: <?php echo htmlspecialchars($first)?> <?php echo htmlspecialchars($lastName)?></h2>
<h2 style="text-align: center" id="logo">Email: <?php echo htmlspecialchars($email1)?></h2>
<h2 style="text-align: center" id="logo">Zipcode: <?php echo htmlspecialchars($zip)?></h2>
<h2 style="text-align: center" id="logo">Number of Listings: <?php echo htmlspecialchars($numListings)?></h2>

</body>
</html>
