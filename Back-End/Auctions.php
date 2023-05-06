<?php
require_once("DBConn.php");
require_once("CSRF.php");

session_start();

if (!CSRF::verifyToken($_POST['csrf_token'])) {
    header("Location: ../Front-End/login.php");
}

else{

    

    if (isset($_POST['sendBid'])) {
        $dbConn = new DBConn();
        $conn = $dbConn->connect();

        $sender_username = $dbConn->getUserFromCookie();
       
        $page = $_POST['pageNo'];
        $itemID = $_POST['itemID'];
        $list_price = $dbConn->getPriceByListing($itemID);
        $bid = $_POST['bidPrice'];
        if($bid > $list_price){
            $dbConn->insertBid($itemID, $sender_username, $bid);
            $dbConn->updateListingPrice($bid, $itemID);
            header("Location: ../Front-End/Homepage.php?pageno=".$page);
        }
        else{
            header("Location: ../Front-End/Homepage.php?error=1");
        }
       

        
    }

    
}