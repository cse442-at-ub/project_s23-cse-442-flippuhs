<?php

require_once('../Back-End/DBConn.php');

if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

function printToConsole($string)
{
    echo '<script>console.log("' . $string . '");</script>';
}


$dbConn = new DBConn();
$conn = $dbConn->connect();

session_start();
unset($_SESSION["sortValue"]);

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

if(isset($_POST["searchListings"])){
    $_SESSION["searchValue"] = $_POST["search"];
}

$total_rows = $dbConn->getNumListingsForSaleSearch($_SESSION["searchValue"]);
$total_pages = ceil($total_rows / $no_of_records_per_page);

printToConsole("SEARCH VALUE IS: ".$_SESSION["searchValue"]);

if (isset($_GET['low'])) {
    $_SESSION["searchSortValue"] = 'low';
}
if (isset($_GET['high'])) {
    $_SESSION["searchSortValue"] = 'high';
}
if($_SESSION["searchSortValue"] == 'low'){
    $resData = $dbConn->getListingsBySearchLow($offset,$no_of_records_per_page,$_SESSION["searchValue"]);
}
else if($_SESSION["searchSortValue"] == 'high'){
    $resData = $dbConn->getListingsBySearchHigh($offset,$no_of_records_per_page,$_SESSION["searchValue"]);
}
else{
    $resData = $dbConn->getListingsBySearch($offset,$no_of_records_per_page, $_SESSION["searchValue"]);
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
<form method="post" action="../Front-End/SearchResults.php">
  <input type="text" name="search">
  <input type="submit" name="searchListings" value="Search"></input>
</form>
<div class="dropdown">
  <button onclick="myFunction()" class='logoutbutton'>Sort</button>
  <div id="myDropdown" class="dropdown-content">
    <a href="../Front-End/SearchResults.php?low">Price: lowest first</a>
    <a href="../Front-End/SearchResults.php?high">Price: highest first</a>
  </div>
</div>
<table style='width:80%'>
	<?php if($resData!=false)while ($row = $resData->fetch_assoc()): ?>
	<tr>
		<td>
        <?php echo "<div id='login-container'>"; ?>
            <?php echo "<img src=" . htmlspecialchars($row['imagepath']) . " class='listing'>".'<br>'; ?>
                <?php echo "<b><p class='signuptext' for='itemName'>Item Name: </b>" . htmlspecialchars($row['itemname']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='itemDescription'>Item Description: </b>" . htmlspecialchars($row['itemdesc']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='price'>Price: </b>" . "$" . htmlspecialchars($row['price']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='seller'>Seller: </b>" . "<a href='LINK'>" . htmlspecialchars($row['username']) . "</p>"; ?>
        <?php echo "</div>" ?>
        </td>
	</tr>
	<?php endwhile; ?>
</table>
<button class="openChatBtn" onclick="openForm()">Messages</button>
<div class="openChat">
    <form id="login-container">
        <h1>Chat</h1>
        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="msg" required></textarea>
        <button type="submit" class="navbarbutton2">Send</button>
        <button type="button" class="navbarbutton1" onclick="closeForm()">
        Close
        </button>
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