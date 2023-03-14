<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>

<body>

<h1>Flippuhs</h1>
<center>
    <h2>Sell For More. Buy For Less.</h2>
    <br>
    <p>Login</p>
    

<form action="home.php" method="post">
    <p>
        <label for="userName">Username:</label>
        <input type="text" name="username" id="userName">
    </p>

    <p>
        <label for="password">Password:</label>
        <input type="text" name="password" id="passWord">
    </p>

    <input type="submit" name="login" value="Submit">
</form>
</center>
<?php
if (isset($_POST['userData'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $user = $_POST['username'];
    $pass = hash('md5', $_POST['password']);
    $reenter = hash('md5', $_POST['repassword']);
    $zipcode = $_POST['zipcode'];

    $servername = "oceanus.cse.buffalo.edu";
    $username = "cho29";
    $password = "50306365";
    $dbname = "cse442_2023_spring_team_v_db";

    // Create Connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error . "\n");
    }
    echo "Connected successfully!\n<br>";

    //Create Table
    $sql = 'CREATE TABLE IF NOT EXISTS UsersTable3 (
        username VARCHAR(50) NOT NULL PRIMARY KEY,
        firstname VARCHAR(50) NOT NULL,
        lastname VARCHAR(50) NOT NULL,
        email VARCHAR(75) NOT NULL,
        password VARCHAR(50) NOT NULL,
        zipcode VARCHAR(10) NOT NULL       
    )';
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'Users' created successfully OR already exists\n<br>";
    } else {
        echo "Error creating table: " . $conn->error . "\n<br>";
    }

    //Check if password match
    if($pass == $reenter){
        //Insert into table
        $sql = "INSERT INTO UsersTable3 (username, firstname, lastname, email, password, zipcode)
        VALUES ('$user','$first_name', '$last_name', '$email', '$pass', '$zipcode')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully\n<br>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error . "\n";
        }
        echo "You have successfully signed up" . $user;
    }
    else{
        echo "Passwords don't match";
    }
    
    
}
?>

</body>
</html>