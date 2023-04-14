<?php
require_once("DBConn.php");

if (isset($_POST['sendMessage'])) {
    $dbConn = new DBConn();
    $conn = $dbConn->connect();

    $sender_username = $dbConn->getUserFromCookie();

    $receiver_username = $_POST['receiver_username'];
    $content = $_POST['content'];

    if(strlen($content) <= 512){
        $dbConn->insertMessage($sender_username, $receiver_username, $content);
        header("Location: ../Front-End/messages.php");
    }
}
