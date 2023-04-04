<?php
if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

$errors = array (
    1 => "Password Incorrect.",
);

$successMsg = "";
$errorMsg = "";

$errorId = isset($_GET['error']) ? (int)$_GET['error'] : 0;
if ($errorId != 0 && array_key_exists($errorId, $errors)) {
    $errorMsg = $errors[$errorId];
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
    <br>
    <p>Login</p>
    

<form action="../Back-End/Login.php" method="post">
    <p>
        <label for="userName">Username:</label>
        <input type="text" name="username" id="userName">
    </p>

    <p>
        <label for="password">Password:</label>
        <input type="password" name="password" id="passWord">
    </p>

    <input type="submit" name="login" value="Submit">
    <p><span style="color:red"><?php echo $errorMsg ?></span></p>
</form>
</center>

</body>
</html>