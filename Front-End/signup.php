<?php
if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

$errors = array (
    1 => "Passwords don't match.",
    2 => "Password needs at least 8 characters.",
    3 => "Password needs at least one character.",
    4 => "Password needs at least one digit.",
    5 => "Password needs at least one special character (!, @, #, $, %, ^, &, or *)",
    6 => "A profile with this email has already been created.",
    7 => "This username is in use.",
    8 => "Password needs at least on uppercase letter.",
);

$successMsg = "";
$errorMsg = "";

$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
}

if(isset($_GET['deleted'])) {
   $deleteMsg = "Your account has been deleted!";
}

?>

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
    <p><span style="color:red"><?php echo $deleteMsg ?></span></p>
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
    <a href="../Front-End/login.php"> Already have an account? Sign in.</a>
    <p><span style="color:red"><?php echo $errorMsg ?></span></p>
</form>
</center>

</body>
</html>