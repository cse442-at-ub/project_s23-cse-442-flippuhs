<!DOCTYPE html>
<html lang="en">

<head>
    <title>Store Data</title>
</head>

<body>
    <center>
        <h1>Storing Form data in Database</h1>
        <form action="database.php" method="post">
            <p>
                <label for="firstName">First Name:</label>
                <input type="text" name="first_name" id="firstName">
            </p>
            <p>
                <label for="lastName">Last Name:</label>
                <input type="text" name="last_name" id="lastName">
            </p>
            <p>
                <label for="emailAddress">Email Address:</label>
                <input type="text" name="email" id="emailAddress">
            </p>

            <input type="submit" name="userData" value="Submit">
        </form>
        <br>
        <br>
        <h1>Uploading Image Form</h1>
        <form action="database.php" method="post" enctype="multipart/form-data">
            <input type="text" placeholder="Username" name="username" id="userName1">
            <br>
            Select image to upload:
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" name="uploadImage" value="Upload Image">
        </form>
        <br>
        <br>
        <h1>Display Profile Picture</h1>
        <form action="database.php" method="post" enctype="multipart/form-data">
            <input type="text" placeholder="Username" name="username" id="userName2">
            <input type="submit" name="getProfilePic" value="Display PFP">
        </form>
    </center>
</body>

</html>