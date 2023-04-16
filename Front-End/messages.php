<?php
require_once('../Back-End/DBConn.php');
require_once("../Back-End/CSRF.php");

if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

$token = CSRF::generateToken();

$errors = array (
    1 => "Can't send message to yourself.",
    2 => "User does not exist."
);

$errorMsg = "";

$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
}

session_start();

$dbConn = new DBConn();
$conn = $dbConn->connect();
$user = $dbConn->getUserFromCookie();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['QUERY_STRING']!="") {
    $query = $_SERVER['QUERY_STRING'];
    $resData = $dbConn->getMessagesWithUser($query);
    $_SESSION["messageUser"] = $query;
}

$usersMessaged = $dbConn->getUsersMessaged();

$messagedArray = array();
if(!$usersMessaged){
}
else{
    while($curRow = $usersMessaged->fetch_assoc()){
        if($curRow["sender_username"]!=$user){
            if(!in_array($curRow["sender_username"],$messagedArray)){
                array_push($messagedArray,$curRow["sender_username"]);
            }
        }
        if($curRow["receiver_username"]!=$user){
            if(!in_array($curRow["receiver_username"],$messagedArray)){
                array_push($messagedArray,$curRow["receiver_username"]);
            }
        }
    }
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
    <h2 class = "messagestitle"> Messages </h2>
    <div class="dropdown">
    <button onclick="myFunction()" style="margin-top:0%" class='messageButton'>Users Messaged:</button>
        <div id="myDropdown" class="dropdown-content">
            <?php
            foreach($messagedArray as $value){
                echo '<a href="../Front-End/messages.php?'.$value.'">'.$value.'</a>';
            }
            ?>
        </div>
    </div>
    <p><span style="color:red"><?php echo $errorMsg ?></span></p>
    <br>
    <?php echo '<h2>Conversation with: '.$query.'</h2>'?>
    <tr>
        <td>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['QUERY_STRING']!="" && $errorMsg=="" && $resData!=false) {
                while ($row = $resData->fetch_assoc()): 
                    if($row["sender_username"]!=$user){
                        echo $row["sender_username"].": ".htmlspecialchars($row["content"])."<br>";
                    }
                    else{
                        echo "Me: ".htmlspecialchars($row["content"])."<br>";
                    }          
                endwhile; 
            }?>
        </td>
    </tr>
    <form id="login-container" class="bottomForm" action="../Back-End/Message.php" method="post" enctype="multipart/form-data">
        <?php 
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['QUERY_STRING']!="" && $errorMsg==""){
            echo '<label for="msg"><b>Message</b></label>
            <textarea class="messagetextarea" placeholder="Type message.." name="content" required></textarea>
            <button type="submit" class="navbarbutton2" name="sendMessageToUser">Send</button>';
        }
        else{
            echo '<h1>Chat</h1>
            <label for ="user"><b>User</b></label>
            <textarea class="messagetextarea" placeholder="Select User" name="receiver_username" required></textarea>
            <label for="msg"><b>Message</b></label>
            <textarea class="messagetextarea" placeholder="Type message.." name="content" required></textarea>
            <button type="submit" class="navbarbutton2" name="sendMessage">Send</button>';
        }
        ?>
        <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    </form>
    <script>
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }
    </script>
</body>

</html>