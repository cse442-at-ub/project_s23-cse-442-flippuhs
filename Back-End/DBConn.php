<?php  
require_once("User.php");

class DBConn {
    public $conn;
    private $servername = "oceanus.cse.buffalo.edu";
    private $username = "cho29";
    private $password = "50306365";
    private $dbname = "cse442_2023_spring_team_v_db";

    private $user;

    function printToConsole($string) {
        echo '<script>console.log("' . $string . '");</script>';
    }

    function connect() {
        // Create Connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->printToConsole("Connected successfully!");
        $this->setupUsersTable();
        $this->setupProfilePicTable();
        $this->setupListingsTable();
        $this->setupMessagesTable();
        $this->setupAuctionsTable();
        return $this->conn;
    }

    function getUserFromCookie() {
        if(!isset($_COOKIE['usernameCookie'])) {
            header("Location: ../Front-End/login.php");
        } 
        else {
            $stmt = $this->conn->prepare("SELECT username FROM UserCookie WHERE hash=?");
            $stmt->bind_param("s", $_COOKIE['usernameCookie']);
            $stmt->execute(); 
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    return $row["username"];
                }
            }
            else {
                header("Location: ../Front-End/login.php");
            }
        }
    }

    function getUnhashUserFromCookie($hash) {
        $stmt = $this->conn->prepare("SELECT username FROM UserCookie WHERE hash=?");
        $stmt->bind_param("s", $hash);
        $stmt->execute(); 
        return $stmt->fetch();
    }

    function insertUsernameHash($username,$hash){
        $stmt = $this->conn->prepare("SELECT * FROM UserCookie WHERE username = ? ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $stmt = $this->conn->prepare("UPDATE UserCookie SET hash=? WHERE username= ?");
            $stmt->bind_param("ss",$hash,$username);
            if($stmt->execute()==true){
                $this->printToConsole("Updated UserCookie path");
            }
            else{
                $this->printToConsole("Failed to update UserCookie");
            }
        }
        else{
            $stmt = $this->conn->prepare("INSERT INTO UserCookie (username, hash)
            VALUES (?,?)");
            $stmt->bind_param("ss",$username,$hash);
            if($stmt->execute()==true){
                $this->printToConsole("Inserted new UserCookie");
            }
            else{
                $this->printToConsole("Failed to insert new UserCookie");
            }
        }
    }

    function setupUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS UsersTable (
            username VARCHAR(50) NOT NULL PRIMARY KEY,
            firstname VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email VARCHAR(75) NOT NULL UNIQUE,
            password VARCHAR(216) NOT NULL,
            zipcode VARCHAR(10) NOT NULL
        )";

        if ($this->conn->query($sql) === TRUE) {
            $this->printToConsole("Table 'UsersTable' created successfully OR already exists");
        } else {
            $this->printToConsole("Error creating table: " . $this->conn->error);
        }
    }

    function setupProfilePicTable() {
        $sql = "CREATE TABLE IF NOT EXISTS ProfilePic (
            username VARCHAR(512) PRIMARY KEY,
            path VARCHAR(2048) NOT NULL
            )";

        if ($this->conn->query($sql) === TRUE) {
            $this->printToConsole("Table 'ProfilePic' created successfully OR already exists");
        } else {
            $this->printToConsole("Error creating table: " . $this->conn->error);
        }
    }

    function setupListingsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS Listings (
            itemid INT PRIMARY KEY AUTO_INCREMENT,
            itemname VARCHAR(2048) NOT NULL,
            itemdesc VARCHAR(2048) NOT NULL,
            price INT NOT NULL,
            imagepath VARCHAR(2048) NOT NULL,
            username VARCHAR(512) NOT NULL,
            itemstatus VARCHAR(50) NOT NULL,
            sellingmethod VARCHAR(50) NOT NULL,
            listTime INT NOT NULL
            )";

        if ($this->conn->query($sql) === TRUE) {
            $this->printToConsole("Table 'Listings' created successfully OR already exists");
        } else {
            $this->printToConsole("Error creating table: " . $this->conn->error);
        }
    }

    function setupMessagesTable() {
        $sql = "CREATE TABLE IF NOT EXISTS Messages (
            sender_username VARCHAR(50) NOT NULL,
            receiver_username VARCHAR(50) NOT NULL,
            content VARCHAR(512) NOT NULL
            )";

        if ($this->conn->query($sql) === TRUE) {
            $this->printToConsole("Table 'Messages' created successfully OR already exists");
        } else {
            $this->printToConsole("Error creating table: " . $this->conn->error);
        }
    }

    function setupAuctionsTable() {
        $sql = "CREATE TABLE IF NOT EXISTS Auctions (
            item_ID INT NOT NULL,
            username VARCHAR(50) NOT NULL,
            bid INT NOT NULL
            )";

        if ($this->conn->query($sql) === TRUE) {
            $this->printToConsole("Table 'Auctions' created successfully OR already exists");
        } else {
            $this->printToConsole("Error creating table: " . $this->conn->error);
        }
    }

    function getEmailExists($email){
        $stmt = $this->conn->prepare("SELECT * FROM UsersTable WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute(); 
        return $stmt->fetch();
    }

    function getUserExists($user){
        $stmt = $this->conn->prepare("SELECT * FROM UsersTable WHERE username=?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        return $stmt->fetch();
    }

    function getUserProfileInfo() {
        $stmt = $this->conn->prepare("SELECT firstname, lastname, email, zipcode FROM UsersTable WHERE username = ? ");
        $stmt->bind_param("s", $this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return new User($row["firstname"],$row["lastname"],$row["email"],$row["zipcode"]);
            }
        } 
        else {
            $this->printToConsole("No such user found!");
            return false;
        }
    }

    function getUserPassword($username) {
        $stmt = $this->conn->prepare("SELECT password FROM UsersTable WHERE username = ? ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return $row["password"];
            }
        } 
        else {
            $this->printToConsole("No such user found!");
        }
    }

    function getUserMessage() {
        $stmt = $this->conn->prepare("SELECT * FROM Messages WHERE sender_username = ? OR receiver_username = ? ");
        $stmt->bind_param("ss", $this->getUserFromCookie(),$this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result;
        } 
        else {
            $this->printToConsole("No such Message found!");
        }
    }

    function getMessagesWithUser($otherUser) {
        $stmt = $this->conn->prepare("SELECT * FROM Messages WHERE sender_username = ? AND receiver_username = ? OR sender_username = ? AND receiver_username = ? ");
        $stmt->bind_param("ssss", $this->getUserFromCookie(),$otherUser,$otherUser,$this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result;
        } 
        else {
            return false;
        }
    }

    function getUsersMessaged() {
        $stmt = $this->conn->prepare("SELECT sender_username,receiver_username FROM Messages WHERE sender_username = ? OR receiver_username = ? ");
        $stmt->bind_param("ss", $this->getUserFromCookie(),$this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result;
        } 
        else {
            return false;
        }
    }

    function getProfilePicPathExists() {
        $stmt = $this->conn->prepare("SELECT path FROM ProfilePic WHERE username= ?");
        $stmt->bind_param("s", $this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["path"];
            }
        }
        else{
            return false;
        }
    }

    function getNumListingsForSale() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Listings WHERE username!=? AND itemstatus=?");
        $forsale = "For sale";
        $stmt->bind_param("ss", $this->getUserFromCookie(),$forsale);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["COUNT(*)"];
            }
        }
        else{
            return false;
        }
    }

    function getListingsForSale($offset, $no_of_records_per_page) {
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $stmt->bind_param("ss", $this->getUserFromCookie(),$forsale);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function validateZipCode($zipCode) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?components=postal_code:$zipCode&key=AIzaSyBZ_HYfAZm6qNE02McacFk32RGvf06d6xY";
        $response = file_get_contents($url);
        $response = json_decode($response);

        if ($response->status == "OK" && count($response->results) > 0) {
            return true;
        } 
        else {
            return false;
        }
    }
      
    function getUserZipcode($username) {
        $stmt = $this->conn->prepare("SELECT zipcode FROM UsersTable WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["zipcode"];
        } 
        else {
            return false;
        }
    }

    function getListName($itemID) {
        $stmt = $this->conn->prepare("SELECT itemname FROM Listings WHERE itemid=?");
        $stmt->bind_param("i", $itemID);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["itemname"];
        } 
        else {
            return false;
        }
    }

    function getUserBid($itemID, $price) {
        $stmt = $this->conn->prepare("SELECT username FROM Auctions WHERE item_ID=? AND bid=?");
        $stmt->bind_param("ii", $itemID,$price);
        $stmt->execute(); 
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["username"];
        } 
        else {
            return "";
        }
    }

    function getDistance($origin, $destination) {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?destinations=$destination&origins=$origin&units=imperial&key=AIzaSyBZ_HYfAZm6qNE02McacFk32RGvf06d6xY";
        $response = file_get_contents($url);
        $response = json_decode($response);
        $dist = $response->rows[0]->elements[0]->distance->text;
        return $dist;
    }
    
    function getAllListingsForSale() {
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=?");
        $forsale = "For sale";
        $stmt->bind_param("ss", $this->getUserFromCookie(),$forsale);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function getListingsForSaleSortedDistance() {
        $rowsWithDistances = array();
        $listings = $this->getAllListingsForSale();
        $userZipcode= $this->getUserZipcode($this->getUserFromCookie());
        if ($listings->num_rows > 0) {
            while ($row = $listings->fetch_assoc()) {
                $dist = $this->getDistance($userZipcode, $this->getUserZipcode($row['username']));
                $row['distance'] = (float)substr($dist, 0, strpos($dist, ' '));
                $rowsWithDistances[] = $row;
            }
            usort($rowsWithDistances, 
            function($a, $b) {
                if ($a['distance'] == $b['distance']) {
                    return $a['itemid'] < $b['itemid'] ? 1 : -1;
                }
                return $a['distance'] > $b['distance'] ? 1 : -1;
            });
            return $rowsWithDistances;
        }
        else{
            return false;
        }
    }

    function getSellerListings($sellerName,$offset, $no_of_records_per_page) {
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username = ? LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $stmt->bind_param("s",$sellerName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function getNumUserListings() {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Listings WHERE username = ?");
        $stmt->bind_param("s", $this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["COUNT(*)"];
            }
        }
        else{
            return false;
        }
    }

    function getUserListings($offset, $no_of_records_per_page) {
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username = ? LIMIT $offset, $no_of_records_per_page");
        $stmt->bind_param("s", $this->getUserFromCookie());
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }
    //Josh
    function getNumListingsByUsername($sellerName){
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Listings WHERE username = ?");
        $stmt->bind_param("s", $sellerName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["COUNT(*)"];
            }
        }
        else{
            return false;
        }
    }

    function getUserInfoByUsername($sellerName){
        $stmt = $this->conn->prepare("SELECT firstname, lastname, email, zipcode FROM UsersTable WHERE username = ? ");
        $stmt->bind_param("s", $sellerName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return new User($row["firstname"],$row["lastname"],$row["email"],$row["zipcode"]);
            }
        } 
        else {
            $this->printToConsole("No such user found!");
            return false;
        }
    }

    function getUserListingsByUsername($sellerName){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username = ?");
        $stmt->bind_param("s", $sellerName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function getProfilePicByUsername($sellerName){
        $stmt = $this->conn->prepare("SELECT path FROM ProfilePic WHERE username= ?");
        $stmt->bind_param("s", $sellerName);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["path"];
            }
        }
        else{
            return false;
        }
    }
    //Josh 
    function getUserListingById($itemID) {
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username = ? AND itemid = ?");
        $stmt->bind_param("si", $this->getUserFromCookie(), $itemID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }
    function getPriceByListing($itemID) {
        $stmt = $this->conn->prepare("SELECT price FROM Listings WHERE  itemid = ?");
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["price"];
        } 
        else {
            return false;
        }
    }

    function updateListingPrice($new_price, $itemID){
        $stmt = $this->conn->prepare("UPDATE Listings SET price=? WHERE itemid= ?");
        $stmt->bind_param("ii",$new_price, $itemID);
        if($stmt->execute()==true){
            $this->printToConsole("Updated listing price");
        }
        else{
            $this->printToConsole("Failed to update listing price");
        }
    }

    function getNumListingsForSaleSearch($search) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM Listings WHERE username!=? AND itemstatus=? AND itemname LIKE ?");
        $forsale = "For sale";
        $searchTerm = "%".$search."%";
        $stmt->bind_param("sss", $this->getUserFromCookie(),$forsale,$searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["COUNT(*)"];
            }
        }
        else{
            return false;
        }
    }

    function getListingsBySearch($offset,$no_of_records_per_page,$search){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? AND itemname LIKE ? LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $searchTerm = "%".$search."%";
        $stmt->bind_param("sss", $this->getUserFromCookie(),$forsale,$searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function getListingsBySearchSortedDistance($search){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? AND itemname LIKE ?");
        $forsale = "For sale";
        $searchTerm = "%".$search."%";
        $userZipcode= $this->getUserZipcode($this->getUserFromCookie());
        $stmt->bind_param("sss", $this->getUserFromCookie(),$forsale,$searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowsWithDistances = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $dist = $this->getDistance($userZipcode, $this->getUserZipcode($row['username']));
                $row['distance'] = (float)substr($dist, 0, strpos($dist, ' '));
                $rowsWithDistances[] = $row;
            }
            usort($rowsWithDistances, 
            function($a, $b) {
                if ($a['distance'] == $b['distance']) {
                    return $a['itemid'] < $b['itemid'] ? 1 : -1;
                }
                return $a['distance'] > $b['distance'] ? 1 : -1;
            });
            return $rowsWithDistances;
        }
        else{
            return false;
        }
    }

    function getListingsLowToHigh($offset,$no_of_records_per_page){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? ORDER BY price ASC LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $stmt->bind_param("ss", $this->getUserFromCookie(),$forsale);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function getListingsHighToLow($offset,$no_of_records_per_page){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? ORDER BY price DESC LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $stmt->bind_param("ss", $this->getUserFromCookie(),$forsale);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function getListingsBySearchLow($offset,$no_of_records_per_page,$search){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? AND itemname LIKE ? ORDER BY price ASC LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $searchTerm = "%".$search."%";
        $stmt->bind_param("sss", $this->getUserFromCookie(),$forsale,$searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }
    
    function getListingsBySearchHigh($offset,$no_of_records_per_page,$search){
        $stmt = $this->conn->prepare("SELECT * FROM Listings WHERE username != ? AND itemstatus=? AND itemname LIKE ? ORDER BY price DESC LIMIT $offset, $no_of_records_per_page");
        $forsale = "For sale";
        $searchTerm = "%".$search."%";
        $stmt->bind_param("sss", $this->getUserFromCookie(),$forsale,$searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result;
        }
        else{
            return false;
        }
    }

    function insertUser($username,$firstname,$lastname,$email,$password,$zipcode){
        $stmt = $this->conn->prepare("INSERT INTO UsersTable (username, firstname, lastname, email, password, zipcode)
        VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$username,$firstname,$lastname,$email,$password,$zipcode);
        if($stmt->execute()==true){
            $this->printToConsole("Inserted new User");
        }
        else{
            $this->printToConsole("Failed to insert new User");
        }
    }


    function insertUserProfilePicPath($target_file){
        $stmt = $this->conn->prepare("INSERT INTO ProfilePic (username, path) VALUES (?,?)");
        $stmt->bind_param("ss",$this->getUserFromCookie(),$target_file);
        if($stmt->execute()==true){
            $this->printToConsole("Inserted profilepic path");
        }
        else{
            $this->printToConsole("Failed to insert profilepic path");
        }
    }

    function insertNewListing($itemname, $itemdesc, $price, $imagepath, $username, $selling_method, $time){
        $stmt = $this->conn->prepare("INSERT INTO Listings (itemname, itemdesc, price, imagepath, username, itemstatus, sellingmethod, listTime) VALUES (?,?,?,?,?,?,?,?)");
        $forsale = "For sale";
        $stmt->bind_param("ssissssi",$itemname, $itemdesc, $price, $imagepath, $username, $forsale, $selling_method, $time);
        if($stmt->execute()==true){
            $this->printToConsole("Inserted new listing");
        }
        else{
            $this->printToConsole("Failed to insert new listing");
        }
    }

    function insertMessage($sender_username,$receiver_username,$content){
        $stmt = $this->conn->prepare("INSERT INTO Messages (sender_username, receiver_username, content)
        VALUES (?,?,?)");
        $stmt->bind_param("sss",$sender_username,$receiver_username,$content);
        if($stmt->execute()==true){
            $this->printToConsole("Inserted new Message");
        }
        else{
            $this->printToConsole("Failed to insert new Message");
        }
    }

    function checkTime($itemID){
        $stmt = $this->conn->prepare("SELECT listTime FROM Listings WHERE itemid = ? ");
        $stmt->bind_param("i", $itemID);
        $stmt->execute();
        $result = $stmt->get_result();
        $value = $result->fetch_object();
        if((time() - $value->listTime) > 259200){
            $sold = "Sold";
            $stmt = $this->conn->prepare("UPDATE Listings SET itemstatus=? WHERE itemid= ?");
            $stmt->bind_param("si",$sold,$itemID);
            $stmt->execute();
            return true;
        }
        else{
            return false;
        }
    }

    function insertBid($itemID,$username,$bid){
        $stmt = $this->conn->prepare("INSERT INTO Auctions (item_ID, username, bid)
        VALUES (?,?,?)");
        $stmt->bind_param("isi",$itemID,$username,$bid);
        if($stmt->execute()==true){
            $this->printToConsole("Inserted new Bid");
        }
        else{
            $this->printToConsole("Failed to insert new Bid");
        }
    }

    function updateUserProfileInfo($firstname, $lastname, $email, $zipcode) {
        //Check if input email is already being used by another user
        $stmt = $this->conn->prepare("SELECT email FROM UsersTable WHERE email = ? ");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return 1;
        }
        else if (!$this->validateZipCode($zipcode)){
            return 2;
        }
        else {
            $stmt = $this->conn->prepare("UPDATE UsersTable SET firstname = COALESCE(NULLIF(?, ''), firstname), lastname = COALESCE(NULLIF(?, ''), lastname), email = COALESCE(NULLIF(?, ''), email), zipcode = COALESCE(NULLIF(?, ''), zipcode) WHERE username = ? ");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $zipcode, $this->getUserFromCookie());
            $stmt->execute();
            $this->printToConsole($stmt->error);
            return 0;
        }
    }

    function updateUserProfilePicPath($target_file){
        $stmt = $this->conn->prepare("UPDATE ProfilePic SET path=? WHERE username= ?");
        $stmt->bind_param("ss",$target_file,$this->getUserFromCookie());
        if($stmt->execute()==true){
            $this->printToConsole("Updated profilepic path");
        }
        else{
            $this->printToConsole("Failed to update profilepic path");
        }
    }

    function updateListing($username, $itemID, $itemName, $itemDescription, $price, $itemstatus) {
        if ($price < 0 || $price > 2147483647) {
            return -2;        
        }
        if ($itemstatus != "For sale" && $itemstatus != "Sold") {
            return -2;
        }
        $result = $this->getUserListingById($itemID);
        if ($result->num_rows > 0) {
            $curListing = null;
            while($row = $result->fetch_assoc()) {
                $curListing = $row;
            }
            if ($itemName == $curListing['itemname'] && $itemDescription == $curListing['itemdesc'] && $price == $curListing['price'] && $itemstatus == $curListing['itemstatus']) {
                return 0;
            }

            $stmt = $this->conn->prepare("UPDATE Listings SET itemname = COALESCE(NULLIF(?, ''), itemname), itemdesc = COALESCE(NULLIF(?, ''), itemdesc), price = COALESCE(NULLIF(?, ''), price), itemstatus = COALESCE(NULLIF(?, ''), itemstatus) WHERE itemid = ? AND username = ?");
            $stmt->bind_param("ssssis", $itemName, $itemDescription, $price, $itemstatus, $itemID, $username);
            $stmt->execute();
            $rowsAffected = $stmt->affected_rows;

            if($rowsAffected > 0){
                return 0;
            }
            else {
                return -1;
            }
        }
        else{
            return -1;
        }  
    }

    function updateListingPicPath($itemID, $target_file){
        $stmt = $this->conn->prepare("UPDATE Listings SET imagepath=? WHERE itemid= ?");
        $stmt->bind_param("si",$target_file,$itemID);
        if($stmt->execute()==true){
            $this->printToConsole("Updated listing image path");
        }
        else{
            $this->printToConsole("Failed to update listing image path");
        }
    }

    function deleteUser() {
        $stmt = $this->conn->prepare("DELETE FROM UsersTable WHERE username= ?");
        $stmt->bind_param("s", $this->getUserFromCookie());
        if($stmt->execute()==true){
            $this->printToConsole("Deleted user from UsersTable");
        }
        else{
            $this->printToConsole("Failed to delete user from UsersTable");
        }
        $stmt = $this->conn->prepare("DELETE FROM ProfilePic WHERE username= ?");
        $stmt->bind_param("s", $this->getUserFromCookie());
        if($stmt->execute()==true){
            $this->printToConsole("Deleted user from ProfilePic");
        }
        else{
            $this->printToConsole("Failed to delete user from ProfilePic");
        }
        $stmt = $this->conn->prepare("DELETE FROM Listings WHERE username= ?");
        $stmt->bind_param("s", $this->getUserFromCookie());
        if($stmt->execute()==true){
            $this->printToConsole("Deleted user from Listings");
        }
        else{
            $this->printToConsole("Failed to delete user from Listings");
        }
        $stmt = $this->conn->prepare("DELETE FROM UserCookie WHERE username= ?");
        $stmt->bind_param("s", $this->getUserFromCookie());
        if($stmt->execute()==true){
            $this->printToConsole("Deleted user from UserCookie");
        }
        else{
            $this->printToConsole("Failed to delete user from UserCookie");
        }
        $stmt = $this->conn->prepare("DELETE FROM Messages WHERE sender_username = ? OR receiver_username = ?");
        $stmt->bind_param("ss", $this->getUserFromCookie(),$this->getUserFromCookie());
        if($stmt->execute()==true){
            $this->printToConsole("Deleted user from Messages");
        }
        else{
            $this->printToConsole("Failed to delete user from Messages");
        }
    }

    function deleteListing($itemID) {
        $stmt = $this->conn->prepare("DELETE FROM Listings WHERE itemid= ?");
        $stmt->bind_param("i", $itemID);
        if($stmt->execute()==true){
            $this->printToConsole("Deleted item from Listings");
        }
        else{
            $this->printToConsole("Failed to delete item from Listings");
        }
    }
   }

?>