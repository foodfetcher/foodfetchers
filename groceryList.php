
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
    
    include 'DButils.php';
	
    $mealid = (int)$_GET["id"];
    $mealInfo = array();
    $db = getDefaultDB();
    
    $res = pg_query($db, "SELECT * FROM meals WHERE mealid='$mealid'");
    if(pg_num_rows($res) == 0){
        $invalidMeal = true;
    }
    else{
        $mealInfo = pg_fetch_assoc($res);
    }
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Grocery List </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <style>
        #background{
                background-image: url(Images/list.jpg);
                background-position-y: calc(-55vw + 80px);
        }
            #printIcon {
                height:10vh;
                position: fixed;
                right: 1vw;
                top: calc(80px + 2vh);
            }
            #groceryList{
                width:100%;
                margin: 10vw 10.2vw 3vh 30vw;
            }
        </style>
        <script>
        </script>
    </head>
    <div id = "background">
        <?php
            include 'nav.php';                           //write out the nav bar
        ?> 
        <div id = "content-container">
            <?php
                include 'sideNavMealPlan.php';          //write out the side nav bar
            ?>
            <img id = "printIcon" src= "Images/print.jpg" alt="Print">
            <div id = "groceryList">
                <?php
                $DB_HOST='localhost';
                                        $DB_USER='fetcher1';
                                        $DB_PASS='1234';
                                        $DB_NAME='main'; 
                                        
                                        $res = pg_query($db, "SELECT * FROM favorites
                                        INNER JOIN recipes ON favorites.recipeid=recipes.recipeid 
                                        WHERE favorites.userid='$userid';");
                                        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                                        $userid = $_SESSION['userid'];
                                        $res = pg_query($db, "SELECT ingredients FROM mealline INNER JOIN
                                        recipes ON mealline.recipeid=recipes.recipeid WHERE mealid=$mealid
                                        ");
                                        echo pg_last_error($db);
                                        while($row = pg_fetch_assoc($res)){
                                        echo $row['ingredients'];;
                                        }
                                        pg_close($db);
                ?>
            </div>
        </div>
    </div>