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
    
    header("cache-control: private");
    header("pragma: private");
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | My Meals </title>
        <link rel="stylesheet" href="phaseIstyle.css">
    </head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <h1> My Meals </h1>
            <a href=createMeal.php>create new meal </a><br/><br/>
            <div id = "results">
                <?php
                    include 'DButils.php';
                    $db = getDefaultDB();
                    
                    $userid = $_SESSION['userid'];
                    $res = pg_query($db, "SELECT * FROM meals WHERE customerid='$userid'");
                    while($row = pg_fetch_assoc($res)){
                        echo '<a href="viewMeal.php?id=' . $row["mealid"] . '">' . $row['mealname'] . '</a></br>';
                    }
                    pg_close($db);
                ?>
            </div>
        </div>
    </body>
</html>
