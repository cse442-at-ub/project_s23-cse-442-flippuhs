<?php
// MySQL DB
if (isset($_POST['userData'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];

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

    //Insert into table
    $sql = "INSERT INTO Users (firstname, lastname, email)
    VALUES ('$first_name', '$last_name', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully\n<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "\n";
    }

    //Select from table
    $sql = "SELECT * FROM Users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "User id: " . $row["id"] . " - Name: " . $row["firstname"] . " " . $row["lastname"] . "<br>";
        }
    } else {
        echo "0 results";
    }
}


if (isset($_POST['uploadImage'])) {
    $user = $_POST['username'];

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
    echo "Connected successfully Upload Image Now!\n<br>";

    //Create Table
    $sql = "CREATE TABLE IF NOT EXISTS ProfilePicTest (
        username VARCHAR(512) PRIMARY KEY,
        path VARCHAR(2048) NOT NULL
        )";

    if ($conn->query($sql) === TRUE) {
        echo "Table 'ProfilePicTest' created successfully OR already exists\n<br>";
    } else {
        echo "Error creating table: " . $conn->error . "\n<br>";
    }


    $target_dir = "resources/pfp/";
    $target_file = $target_dir . $user . "_" . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        echo $target_file;
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            //Check if user already has PFP uploaded so UPDATE instead of INSERT
            $sql = "SELECT path FROM ProfilePicTest WHERE username='$user'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $sql = "UPDATE ProfilePicTest SET path='$target_file' WHERE username='$user'";
            } else {
                $sql = "INSERT INTO ProfilePicTest (username, path) VALUES ('$user', '$target_file')";
            }
            if ($conn->query($sql) === TRUE) {
                echo "New record for PFP created successfully\n<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error . "\n";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}


if (isset($_POST['getProfilePic'])) {
    //Get username from form
    $user = $_POST['username'];

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
    echo "Connected successfully Getting Profile Picture!\n<br><br>";

    //Select from table
    $sql = "SELECT path FROM ProfilePicTest WHERE username='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "Hello $user, here is your current profile picture<br>";
            echo "<img src='$row[path]'  width='200' height='200'>";
        }
    } else {
        echo "You do not have a profile picture uploaded!";
    }
}
?>