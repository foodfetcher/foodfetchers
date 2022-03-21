<?php
	session_start();
	if (isset($_SESSION['userid']))
	{
		$log = "Logout";
    }
	else
	{
		$log = "Login";
		header("Location: home.php");
		die('<a href="home.php">Click here if you are not automatically redirected</a>');
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Your Account </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <h1> Your Profile</h1>
			<div id="Info">
				<?php
					$email = $_SESSION["email"];
					$firstname = $_SESSION["firstname"];
					$lastname = $_SESSION["lastname"];
					echo "<div id='Name'>Name: $firstname $lastname</div>";
					echo "<div id='Email'>Email: $email</div>";
				?>
				<a id="password" href=changePassword.php>Change Password</a>
			</div>
        </div>
    </body>
</html>
