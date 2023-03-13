<?php  
require_once("User.php");

class DBConn {
    public $conn;
    private $servername = "oceanus.cse.buffalo.edu";
    private $username = "cho29";
    private $password = "50306365";
    private $dbname = "cse442_2023_spring_team_v_db";

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
        return $this->conn;
    }

    function setupUsersTable() {
        $sql = "CREATE TABLE IF NOT EXISTS UsersTable (
            username VARCHAR(50) NOT NULL PRIMARY KEY,
            firstname VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email VARCHAR(75) NOT NULL UNIQUE,
            password VARCHAR(50) NOT NULL,
            zipcode VARCHAR(10) NOT NULL
        )";

        if ($this->conn->query($sql) === TRUE) {
            $this->printToConsole("Table 'UsersTable' created successfully OR already exists");
        } else {
            $this->printToConsole("Error creating table: " . $this->conn->error);
        }
    }

    function getUserByUsername() {
        //Commented cookie handling temporarily until login and registration are done
        /*
        $usernameCookie = "username";
        if(!isset($_COOKIE[$usernameCookie])) {
            echo "Username cookie is not set!";
        } 
        else {
            //$username = $_COOKIE[$usernameCookie];
        }
        */
    }

    function getUserProfileInfo() {
        $username = "johndoe"; //Temporary dummy value
        $stmt = $this->conn->prepare("SELECT firstname, lastname, email, zipcode FROM UsersTable WHERE username = ? ");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                return new User($row["firstname"],$row["lastname"],$row["email"],$row["zipcode"]);
            }
        } 
        else {
            $this->printToConsole("No such user found!");
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
        else {
            $username = "johndoe"; //Temporary dummy value
            $stmt = $this->conn->prepare("UPDATE UsersTable SET firstname = COALESCE(NULLIF(?, ''), firstname), lastname = COALESCE(NULLIF(?, ''), lastname), email = COALESCE(NULLIF(?, ''), email), zipcode = COALESCE(NULLIF(?, ''), zipcode) WHERE username = ? ");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $zipcode, $username);
            $stmt->execute();
            $this->printToConsole($stmt->error);
            return 0;
        }
    }
}

?>