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

// Get the current page number from the URL parameter, default to 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Set the number of listings to display per page
$perPage = 2;

// Get the sorted list of listings
$listings = $dbConn->getListingsForSaleSortedDistance();

// Calculate the total number of pages based on the number of listings and the per-page limit
$totalPages = ceil(count($listings) / $perPage);

// Get the subset of listings for the current page based on the page number and per-page limit
$startIndex = ($page - 1) * $perPage;
$endIndex = min($startIndex + $perPage, count($listings));
$pageListings = array_slice($listings, $startIndex, $endIndex - $startIndex);

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
    <li><a class='logoutbutton' href="?page=1">First</a></li>
    <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
        <a class='logoutbutton' href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Prev</a>
    </li>
    <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
        <a class='logoutbutton' href="<?php if($page >= $totalPages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a>
    </li>
    <li><a class='logoutbutton' href="?page=<?php echo $totalPages; ?>">Last</a></li>
</ul>

<div class="dropdown" style="margin-left:1%">
  <button onclick="myFunction()" class='logoutbutton'>Sort By</button>
  <div id="myDropdown" class="dropdown-content">
    <a href="../Front-End/DistanceResults.php">Distance: nearest first</a>
    <a href="../Front-End/Homepage.php?low">Price: lowest first</a>
    <a href="../Front-End/Homepage.php?high">Price: highest first</a>
  </div>
</div>

<table style='width:100%'>
    <?php foreach ($pageListings as $listing): ?>
    <tr>
        <td>
        <?php echo "<div id='login-container'>"; ?>
        <?php echo "<img src=" . htmlspecialchars($listing['imagepath']) . " class='listing'>".'<br>'; ?>
            <?php echo "<b><p class='signuptext' for='itemName'>Item Name: </b>" . htmlspecialchars($listing['itemname']) . "</p>"; ?>

            <?php echo "<b><p class='signuptext' for='itemDescription'>Item Description: </b>" . htmlspecialchars($listing['itemdesc']) . "</p>"; ?>

            <?php echo "<b><p class='signuptext' for='price'>Price: </b>" . "$". htmlspecialchars($listing['price']) . "</p>"; ?>

            <?php echo "<b><p class='signuptext' for='price'>Distance: </b>" . htmlspecialchars($listing['distance']) . " miles" . "</p>"; ?>
            
            <?php echo "<b><p class='signuptext' for='seller'>Seller: </b>" . "<a href=../Front-End/SellerProfile.php?sellername={$listing['username']}>" . htmlspecialchars($listing['username']) . "</a></p>"; ?>
        <?php echo "</div>" ?>
        </td>
    </tr>
    <?php endforeach; ?>
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