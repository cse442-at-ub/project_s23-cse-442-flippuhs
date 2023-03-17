<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>

<body>
<h1>Flippuhs</h1>

<center>
    <h2>Sell For More. Buy For Less.</h2>
    <br><br><br>
    <p>Sign up</p>

<form action="../Back-End/SignUp.php" method="post">
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

    <p>
        <label for="userName">Username:</label>
        <input type="text" name="username" id="userName">
    </p>

    <p>
        <label for="zipCode">Zipcode:</label>
        <input type="text" name="zipcode" id="zipCode">
    </p>

    <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="passWord">
    </p>

    <p>
        <label for="repassword">Re-enter Password:</label>
        <input type="password" name="repassword" id="repassWord">
    </p>

    <input type="submit" name="userData" value="Submit">
</form>
</center>

</body>
</html>