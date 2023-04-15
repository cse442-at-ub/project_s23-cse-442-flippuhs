<?php
require_once("DBConn.php");

session_start();

if (isset($_POST['sendMessage'])) {
    $dbConn = new DBConn();
    $conn = $dbConn->connect();

    $sender_username = $dbConn->getUserFromCookie();

    $receiver_username = $_POST['receiver_username'];
    $content = $_POST['content'];

    if(strlen($content) <= 512 && $receiver_username != $sender_username){
        if($dbConn->getUserExists($receiver_username) == true){ // check to make sure they dont message themselves and that the user to message exists
            $dbConn->insertMessage($sender_username, $receiver_username, $content);
            header("Location: ../Front-End/messages.php?".$receiver_username);
        }
        else{
            header("Location: ../Front-End/messages.php?error=2");
        }
    }
    else{
        header("Location: ../Front-End/messages.php?error=1");
    }
}

if (isset($_POST['sendMessageToUser'])) {
    $dbConn = new DBConn();
    $conn = $dbConn->connect();

    $sender_username = $dbConn->getUserFromCookie();

    $receiver_username = $_SESSION["messageUser"];
    $content = $_POST['content'];

    if(strlen($content) <= 512 && $receiver_username != $sender_username){
        if($dbConn->getUserExists($receiver_username) == true){ // check to make sure they dont message themselves and that the user to message exists
            $dbConn->insertMessage($sender_username, $receiver_username, $content);
            header("Location: ../Front-End/messages.php?".$receiver_username);
        }
        else{
            header("Location: ../Front-End/messages.php?error=2");
        }
    }
    else{
        header("Location: ../Front-End/messages.php?error=1");
    }
}
