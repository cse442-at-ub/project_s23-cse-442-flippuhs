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
    $pfp = $dbConn->getProfilePicByUsername($seller);
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

if (isset($_GET['pageno'])) {
    $pageno = $_GET['pageno'];
} else {
    $pageno = 1;
}

$no_of_records_per_page = 2;
$offset = ($pageno-1) * $no_of_records_per_page; 

$total_rows = $numListings;
$total_pages = ceil($total_rows / $no_of_records_per_page);

$resData = $dbConn->getSellerListings($seller,$offset,$no_of_records_per_page);

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
<h2 style="text-align: center" id="logo">Name: <?php echo htmlspecialchars($first)?> <?php echo htmlspecialchars($last)?></h2>
<h2 style="text-align: center" id="logo">Email: <?php echo htmlspecialchars($email1)?></h2>
<h2 style="text-align: center" id="logo">Zipcode: <?php echo htmlspecialchars($zip)?></h2>
<h2 style="text-align: center" id="logo">Number of Listings: <?php echo htmlspecialchars($numListings)?></h2>
<br><br>
<div class="center">
    <a class='messageSellerButton' href=<?php echo '../Front-End/messages.php?'.$seller?>>Message Seller</a>
</div>
<ul class="pagination">
    <li><a class='logoutbutton' href=<?php echo "?sellername=".$seller."&"."pageno=1"?>>First</a></li>
    <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
        <a class='logoutbutton' href="<?php if($pageno <= 1){ echo '#'; } else { echo "?sellername=".$seller."&"."pageno=".($pageno - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
        <a class='logoutbutton' href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?sellername=".$seller."&"."pageno=".($pageno + 1); } ?>">Next</a>
    </li>
    <li><a class='logoutbutton' href=<?php echo "?sellername=".$seller."&"."pageno=".$total_pages; ?>>Last</a></li>
</ul>
<table style='width:80%'>
	<?php if($resData!=false)while ($row = $resData->fetch_assoc()): ?>
	<tr>
		<td>
        <?php echo "<div id='login-container'>"; ?>
            <?php echo "<img src=" . htmlspecialchars($row['imagepath']) . " class='listing'>".'<br>'; ?>
                <?php echo "<b><p class='signuptext' for='itemName'>Item Name: </b>" . htmlspecialchars($row['itemname']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='itemDescription'>Item Description: </b>" . htmlspecialchars($row['itemdesc']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='price'>Price: </b>" . "$".htmlspecialchars($row['price']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='seller'>Seller: </b>" . htmlspecialchars($row['itemstatus']) . "</p>"; ?>
                
        <?php echo "</div>" ?>
        </td>
	</tr>
	<?php endwhile; ?>
</table>
</body>
</html>
