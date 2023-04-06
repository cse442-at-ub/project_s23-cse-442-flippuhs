<?php
    if($dbConn->getUserProfileInfo()!=false){
        //check that usernameCookie exists or else redirect to login page
        $userInfo = $dbConn->getUserProfileInfo();
        $firstName = $userInfo->getFirstName();
        $lastName = $userInfo->getLastName();
        $email = $userInfo->getEmail();
        $zipcode = $userInfo->getZipcode();
        $pfpPath = $dbConn->getProfilePicPathExists();
        if($pfpPath!=false){
            $pfpPath = $dbConn->getProfilePicPathExists();
        }
        else{
            $pfpPath = "../resources/pfp/defaultPFP.jpg";
        }
    }
    else{
        header("Location: ../Front-End/login.php");
    }
?>

<head>
<link rel="stylesheet" href="styles.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
    <nav class="navbar" style="background-color: #333;">
        <div id="center" class="container-fluid">
            <a class="navbar-brand" href="../Front-End/Homepage.php"><h1 id="logo">Flippuhs</h1></a>
            <div class="mx-auto">
                <a class="navbarbutton" href="../Front-End/CreateListing.php">Create a Listing</a>
                <a class="navbarbutton" href="../Front-End/UserListings.php">My Listings</a>
            </div>
            <div class="ms-auto">
                <form action="../Back-End/Login.php" method="POST">
                    <input class="logoutbutton" type="submit" name="logout" value="Logout"/>
                </form>
            </div>
            <div class="ms-auto">
                <a href="../Back-End/ProfilePage.php">
                    <img id="navbarpfp" src="<?php echo $pfpPath?>"/>
                </a>
            </div>
        </div>
    </nav>
</body>
