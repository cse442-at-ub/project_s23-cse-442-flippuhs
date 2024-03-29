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
        $itemID = intval(str_replace("/","",$_POST['itemID']));
        $list_price = $dbConn->getPriceByListing($itemID);
        $timeCheck = $dbConn->checkTime($itemID);
        $bid = $_POST['bidPrice'];
        echo $timeCheck;
        
        $item_name = $dbConn->getListName($itemID);
        if($timeCheck){
            header("Location: ../Front-End/Homepage.php?error=2&itemname=" . $item_name);
        }
        elseif($bid > $list_price){
            $dbConn->insertBid($itemID, $sender_username, $bid);
            $dbConn->updateListingPrice($bid, $itemID);
            header("Location: ../Front-End/Homepage.php?pageno=".$page);
        }
        else{
            header("Location: ../Front-End/Homepage.php?error=1&itemname=" . htmlspecialchars($item_name));
        }
        
       

        
    }

    
}