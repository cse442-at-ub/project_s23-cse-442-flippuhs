<?php
require_once('../Back-End/DBConn.php');

if(!isset($_SERVER['HTTPS'])||$_SERVER['HTTPS']!='on'){
    header('Location: '.
    'https://'.
    $_SERVER['SERVER_NAME'].
    $_SERVER['PHP_SELF']);
}

$dbConn = new DBConn();
$conn = $dbConn->connect();
$user = $dbConn->getUserFromCookie();
$resData = $dbConn->getUserMessage();
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <?php include 'navbar.php';?>
    <h2 class = "messagestitle"> Messages </h2>
    <?php echo '<table> <tr> <th> Name </th> <th> Receiver </th> <th> Message </th> </tr>';
    while ($row = $resData->fetch_assoc()): 
        
        echo '<tr > <td>' . $row["sender_username"] . '</td>
        <td>' . $row["receiver_username"] . '</td>
        <td> ' . $row["content"] . '</td></tr>';
        
        
	endwhile; 
    echo '</table>';?>

</body>

</html>