<?php
// MySQL DB

//if (isset($_POST['submit'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
//}

$servername = "oceanus.cse.buffalo.edu";
$username = "cho29";
$password = "50306365";
$dbname = "cse442_2023_spring_team_v_db";

// Create Connection
//$conn = new mysqli($servername, $username, $password);
$conn = new mysqli($servername, $username, $password, $dbname);


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error . "\n");
}
echo "Connected successfully!\n<br>";

$sql = "CREATE TABLE IF NOT EXISTS Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    email VARCHAR(50),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
if ($conn->query($sql) === TRUE) {
    echo "Table 'Users' created successfully OR already exists\n<br>";
} else {
    echo "Error creating table: " . $conn->error . "\n<br>";
}

$sql = "INSERT INTO Users (firstname, lastname, email)
VALUES ('$first_name', '$last_name', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully\n<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error . "\n";
}

$sql = "SELECT * FROM Users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "User id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
  }
} else {
  echo "0 results";
}
?>