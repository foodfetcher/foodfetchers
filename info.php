<?php
	session_start();
	if (isset($_SESSION['userid']))
	{
		$log = "Logout";
    }
	else
	{
		$log = "Login";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | info </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        
    </head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <h1> Info</h1>
        </div>
    </body>
</html>
