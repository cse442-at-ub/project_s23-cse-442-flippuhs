<?php
require_once("../Back-End/CSRF.php");

$token = CSRF::generateToken();

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
    9 => "Username has to be alphanumeric (no special characters).",
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
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<title>Sign Up on Flippuhs</title>
</head>

<body>
<?php include 'navbarLoggedOut.php';?>

<form id="login-container" action="../Back-End/SignUp.php" method="post">
    <div class="form-group">
        <label class="signuptext" for="firstName">First Name:</label>
        <input type="text" name="first_name" id="firstName">
    </div>
    <div class="form-group">
        <label class="signuptext" for="lastName">Last Name:</label>
        <input type="text" name="last_name" id="lastName">
        </div>
    <div class="form-group">
        <label class="signuptext" for="emailAddress">Email Address:</label>
        <input type="email" name="email" id="emailAddress">
    </div>
    <div class="form-group">
        <label class="signuptext" for="userName">Username:</label>
        <input type="text" name="username" id="userName">
    </div>
    <div class="form-group">
        <label class="signuptext" for="zipCode">Zipcode:</label>
        <input type="text" name="zipcode" id="zipCode">
    </div>
    <div class="form-group">
        <label class="signuptext" for="password">Password:</label>
        <input type="password" name="password" id="passWord">
    </div>
    <div class="form-group">
        <label class="signuptext" for="repassword">Re-enter Password:</label>
        <input type="password" name="repassword" id="repassWord">
    </div>

    <input class="login-button" type="submit" name="userData" value="Sign Up">

    <a href="../Front-End/login.php"> Already have an account? Sign in.</a>
    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
    <p><span style="color:red"><?php echo $errorMsg ?></span></p>
</form>

</body>
</html>