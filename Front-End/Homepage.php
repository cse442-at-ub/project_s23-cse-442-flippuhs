<?php

require_once('../Back-End/DBConn.php');

/*
if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}
*/

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

$total_rows = $dbConn->getNumListingsForSale();
$total_pages = ceil($total_rows / $no_of_records_per_page);

$resData = $dbConn->getListingsForSale($offset,$no_of_records_per_page);

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
<table style='width:80%'>
	<?php if($resData!=false)while ($row = $resData->fetch_assoc()): ?>
	<tr>
		<td>
        <?php echo "<div id='login-container'>"; ?>
            <?php echo "<img src=" . htmlspecialchars($row['imagepath']) . " class='listing'>".'<br>'; ?>
                <?php echo "<b><p class='signuptext' for='itemName'>Item Name: </b>" . htmlspecialchars($row['itemname']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='itemDescription'>Item Description: </b>" . htmlspecialchars($row['itemdesc']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='price'>Price: </b>" . htmlspecialchars($row['price']) . "</p>"; ?>

                <?php echo "<b><p class='signuptext' for='seller'>Seller: </b>" . "<a href=../Front-End/SellerProfile.php?username={$row['username']}>" . htmlspecialchars($row['username']) . "</p>"; ?>
        </a>
                
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
 </script>


</body>
</html>