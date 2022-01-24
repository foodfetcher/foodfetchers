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
    
    $mealid = (int)$_GET["id"];
    $mealInfo = array();
    $DB_HOST='localhost';
    $DB_USER='fetcher1';
    $DB_PASS='1234';
    $DB_NAME='main'; 
    $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
    
    $res = pg_query($db, "SELECT * FROM meals WHERE mealid='$mealid'");
    if(pg_num_rows($res) == 0){
        $invalidMeal = true;
    }
    else{
        $mealInfo = pg_fetch_assoc($res);
    }
    
    
    if(isset($_POST["favorite"])){
        $userid = $_SESSION["userid"];
        $res = pg_query($db, "INSERT INTO favorites (recipeid, userid) values ($recipeid, $userid);");
        echo pg_last_error($db);
    }
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | <?php echo $mealInfo["mealname"] ?? "Unknown Meal"; ?> </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <style>
            #restable {
            border-collapse: collapse;
            width: 40%;
            margin: auto;
            }
            
            #restable td, #restable th {
            border: 1px solid #000000;
            text-align: left;
            padding: 8px;
            }
            
            #restable tr:nth-child(even) {
            background-color: #f9e27f;
            }
        </style>
    </head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <h1> <?php echo $mealInfo["mealname"] ?? "Unknown Meal"; ?> </h1>
            <div id = "results">
                <?php
                    echo "<table id=restable>";
                    echo "<th>day</th>";
                    echo "<th>recipes on that day</th>";
                    foreach(array(0,1,2,3,4,5,6) as $day){
                        echo "<tr><td>$day</td><td>";
                        $res = pg_query($db, "SELECT * FROM mealline WHERE day=$day AND mealid=$mealid;");
                        echo pg_last_error($db);
                        while($row = pg_fetch_assoc($res)){
                            //print_r($row);
                            echo $row["recipeid"] . " ";
                        }
                        echo "</td></tr>";
                    }
                    echo "</table>";
                    pg_close($db);
                ?>
            </div>
        </div>
    </body>
</html>
