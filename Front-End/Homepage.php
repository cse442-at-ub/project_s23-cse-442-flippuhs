<?php
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
<?php include 'navbar.php';?>

<button class="openChatBtn" onclick="openForm()">Messages</button>
<div class="openChat">
    <form id="login-container">
        <h1>Chat</h1>
        <label for="msg"><b>Message</b></label>
        <textarea placeholder="Type message.." name="msg" required></textarea>
        <button type="submit" class="navbarbutton2">Send</button>
        <button type="button" class="navbarbutton1" onclick="closeForm()">
        Close
        </button>
    </form>
</div>
<script>
    document .querySelector(".openChatBtn") .addEventListener("click", openForm);
    document.querySelector(".close").addEventListener("click", closeForm);
    function openForm() {
       document.querySelector(".openChat").style.display = "block";
    }
    function closeForm() {
       document.querySelector(".openChat").style.display = "none";
    }
 </script>

</body>
</html>