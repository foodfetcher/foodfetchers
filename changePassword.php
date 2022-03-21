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
            <h1> Change Password</h1>
			<div id="Info">
				<?php
				
				?>
			</div>
        </div>
    </body>
</html>
