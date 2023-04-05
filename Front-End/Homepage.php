<?php
require_once('../Back-End/DBConn.php');

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
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>

<body>
<div style = "position: absolute; left:40px; top:0px;">
    <h1>Flippuhs</h1>
    
</div>

<div style = "position: absolute; left:40px; top:50px;">
    <h2>Sell For More. Buy For Less.</h2>
</div>

<div style = "position: absolute; left:600px; top:60px; ;" class="topnav">
    <input type="text" placeholder="Search..">
</div>


<button class="container" onClick="window.location.href='../Back-End/ProfilePage.php';">
    <img src="../resources/pfp/defaultPFP.jpg" style = "position: absolute; width: 90px; height: 90px; top: 0px; right: 0px;" />
</button>


<img src= "../resources/items/silversurfer.jpeg"  class = "defaultSearch1" />
<img src= "../resources/items/bigpants.jpeg"  class = "defaultSearch2" />
<img src= "../resources/items/coffee.jpeg"  class = "defaultSearch3" />
<img src= "../resources/items/denver.jpeg"  class = "defaultSearch4" />
<img src= "../resources/items/spam.jpeg"  class = "defaultSearch5" />
<img src= "../resources/items/rick.jpeg"  class = "defaultSearch6" />

<div class = "defaultText1">
    <h3>Yeezy's $250</h3>
</div>
<div class = "defaultText2">
    <h3>Big Pants $80</h3>
</div>
<div class = "defaultText3">
    <h3>Coffee Table $60</h3>
</div>
<div class = "defaultText4">
    <h3>Denver Nugget Jeans $210</h3>
</div>
<div class = "defaultText5">
    <h3>Vintage Spam $44</h3>
</div>
<div class = "defaultText6">
    <h3>Rick Owen's $790</h3>
</div>
<div class = "white-box">

</div>
<div style =  "position: absolute; left:820px; top:70px;">
    <ul>
        <li><a href="../Front-End/Homepage.php"> Create a Post</a></li>
        <li><a href="../Back-End/ProfilePage.php">Profile</a></li>
        <li><a href="../Front-End/Homepage.php">Electronics</a></li>
        <li><a href="../Front-End/Homepage.php">Clothing</a></li>
  </ul>
</div>

<form action="../Back-End/Login.php" method="POST">
    <input type="submit" name="logout" value="Logout"/>
</form>


  


<button class="openChatBtn" onclick="openForm()">Messages</button>
<div class="openChat">
    <form>
        <h1>Chat</h1>
        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="msg" required></textarea>
        <button type="submit" class="btn">Send</button>
        <button type="button" class="btn close" onclick="closeForm()">
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