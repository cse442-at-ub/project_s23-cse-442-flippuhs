<?php
require_once('../Back-End/DBConn.php');
require_once("../Back-End/CSRF.php");

$token = CSRF::generateToken();

if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
} 


$errors = array (
    1 => "Bid must be higher than the current price",
);

$errorMsg = "";

$itemErrorId = isset($_GET['itemdesc']) ? (string)$_GET['itemdesc'] : null;
$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
    $errorMsg = $errorMsg . " on listing, " . strval($itemErrorId);
}


$dbConn = new DBConn();
$conn = $dbConn->connect();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['QUERY_STRING']!="") {
    $query = $_SERVER['QUERY_STRING'];
    $resData = $dbConn->getMessagesWithUser($query);
    $_SESSION["messageUser"] = $query;
}

session_start();
unset($_SESSION['searchValue']);

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

$total_rows = $dbConn->getNumListingsForSale();
$total_pages = ceil($total_rows / $no_of_records_per_page);

if (isset($_GET['low'])) {
    $_SESSION["sortValue"] = 'low';
}
if (isset($_GET['high'])) {
    $_SESSION["sortValue"] = 'high';
}
if($_SESSION["sortValue"] == 'low'){
    $resData = $dbConn->getListingsLowToHigh($offset,$no_of_records_per_page);
}
else if($_SESSION["sortValue"] == 'high'){
    $resData = $dbConn->getListingsHighToLow($offset,$no_of_records_per_page);
}
else{
    $resData = $dbConn->getListingsForSale($offset,$no_of_records_per_page);
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
<form method="post" action="SearchResults.php">
  <input type="text" name="search" style="margin-left:1%">
  <input type="submit" name="searchListings" style="margin-top:0%" class='logoutbutton' value="Search"></input>
</form>
<div class="dropdown" style="margin-left:1%">
  <button onclick="myFunction()" style="margin-top:0%;" class='logoutbutton'>Sort By</button>
  <div id="myDropdown" class="dropdown-content">
    <a href="../Front-End/DistanceResults.php">Distance: nearest first</a>
    <a href="../Front-End/Homepage.php?low">Price: lowest first</a>
    <a href="../Front-End/Homepage.php?high">Price: highest first</a>
  </div>
</div>
<p><span style="color:red"><?php echo $errorMsg ?></span></p>
<table style='width:100%'>
	<?php if($resData!=false) while ($row = $resData->fetch_assoc()):     
        $dist = $dbConn->getDistance($dbConn->getUserZipcode($dbConn->getUserFromCookie()), $dbConn->getUserZipcode($row['username']));
        $row['distance'] = (float)substr($dist, 0, strpos($dist, ' ')); 
    ?>
	<tr>
		<td>
        <?php echo "<div id='login-container'>"; ?>
        <?php $bidName = $dbConn->getUserBid($row['itemid'], $row['price']); ?>
            <?php echo "<img src=" . htmlspecialchars($row['imagepath']) . " class='listing'>".'<br>'; ?>
                <?php echo "<b><p class='signuptext' for='itemName'>Item Name: </b>" . htmlspecialchars($row['itemname']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='itemDescription'>Item Description: </b>" . htmlspecialchars($row['itemdesc']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='sellingMethod'>Selling Method: </b>" . htmlspecialchars($row['sellingmethod']) . "</p>"; ?>
                <?php if($row['sellingmethod'] == "Auction" && strlen($bidName) != 0){
                    echo "<b><p class='signuptext' for='price'>Price: </b>" . "$". htmlspecialchars($row['price']) . " ~ User: ". htmlspecialchars($bidName)."</p>"; 
                } 
                elseif($row['sellingmethod'] == "Auction" && strlen($bidname) == 0){
                    echo "<b><p class='signuptext' for='price'>Price: </b>" . "$". htmlspecialchars($row['price']) ." Be the first to place a bid!</p>";
                } 
                else{
                    echo "<b><p class='signuptext' for='price'>Price: </b>" . "$". htmlspecialchars($row['price']) ."</p>";
                } 
                ?>
                
                <?php if($row['sellingmethod'] == "Auction"): ?>
                    
                        <form  action="../Back-End/Auctions.php" method='post'>
                        <div class='form-group'>
                        <label class='signuptext' for='sellingmethod'>Bid:  </b> </label>
                        <?php echo "<input type='number' name='bidPrice' id='bidPrice' maxlength='12' required='required' min='0' max='2147483647' placeholder='Must be greater than $" . htmlspecialchars($row['price']) . "'/>" ; ?>
                        <?php echo "<input type='hidden' name='itemID' value=" . htmlspecialchars($row['itemid']). "/>"; ?>
                        <?php echo "<input type='hidden' name='pageNo' value=" . htmlspecialchars($pageno). "/>"; ?>
                        <button type='submit' class='navbarbutton2' name='sendBid'>Bid</button>
                        </div>
                        </form>
                        
                <?php endif ?>


                

                
                <?php echo "<b><p class='signuptext' for='price'>Distance: </b>" . htmlspecialchars($row['distance']) . " miles" . "</p>"; ?>

                


                <?php echo "<b><p class='signuptext' for='seller'>Seller: </b>" . "<a href=../Front-End/SellerProfile.php?sellername={$row['username']}>" . htmlspecialchars($row['username']) . "</a></p>"; ?>
        <?php echo "</div>" ?>
        </td>
	</tr>
	<?php endwhile; ?>
</table>
<button class="openChatBtn" onclick="openForm()">Messages</button>
<div class="openChat">
    <form id="login-container" action="../Back-End/Message.php" method="post" enctype="multipart/form-data">
        <h1>Chat</h1>
        <label for ="user"><b>User</b></label?>
        <textarea placeholder="Select User" name="receiver_username" required></textarea>
        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="content" required></textarea>
        <button type="submit" class="navbarbutton2" name="sendMessage">Send</button>
        <button type="button" class="navbarbutton1" onclick="closeForm()">
        Close
        </button>
        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    </form>
</div>
<script>
    document .querySelector(".openChatBtn") .addEventListener("click", openForm);
    document.querySelector(".close").addEventListener("click", closeForm);
    function openForm() {
       document.querySelector(".openChat").style.display = "block";
    }
    function closeForm() {
       document.querySelector(".openChat").style.display = "none";
    }
    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
 </script>

</body>
</html>