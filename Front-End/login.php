<?php
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
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title>Login to Flippuhs</title>
</head>

<body>
<?php include 'navbarLoggedOut.php';?>

<form id="login-container" action="../Back-End/Login.php" method="post">
    <div class="form-group">
        <label class="signuptext" for="userName">Username:</label>
        <input type="text" name="username" id="userName">
    </div>
    <div class="form-group">
        <label class="signuptext" for="password">Password:</label>
        <input type="password" name="password" id="passWord">
    </div>
    <input class="login-button" type="submit" name="login" value="Login">
    <p><span style="color:red"><?php echo $errorMsg ?></span></p>
</form>

</body>
</html>