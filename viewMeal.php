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
            #content-container{
                height: calc(100vh - 80px);
                display:flex;
            }
            #plan-display{
                width: 85vw;
                height:90%;
                margin: auto;
                background-color: var(--color2-darkGreyT);
                display:flex;
                flex-wrap: wrap;
                align-content: flex-start;
            }
            #plan-title{
                width:83vw;
                height:10%;
                background-color: var(--color1-white);
                margin: 2vh 1vw;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            #plan-title h1{
                font-size: 5vh;
                margin: 0;
                background-color: var(--color1-white);
                width: 83vw;
                height: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            #week-display {
                height:calc(90% - 4vh);
                display:flex;
                flex-direction: row;
                padding: 0 1vw;
                gap: 1vw;
                flex:1;
            }
            #week-display h2{
                font-size: 3vh;
                margin: 1vh .25vw 1vh .25vw;
                border-bottom: 1px solid black;
                padding: 0 0 1.25vh 0;
            }
            #week-display ol{
                padding-right: 1vw;
            }
            .day {
                height: calc(100% - 2vh);;
                flex:1;
                background-color: var(--color1-white);
            }
        </style>
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "content-container">
            <?php
            include 'sideNavMealPlan.php'; //write out the nav bar
            ?> 
            <div id = "plan-display">
                <div id= "plan-title">
                    <h1> <?php echo $mealInfo["mealname"] ?? "Unknown Plan"; ?> </h1>
                </div>
                <div id = "week-display">
                    <?php
                    $weekDays = array("Sunday","Monday","Tuesday","Wednsday","Thursday","Friday","Saturday");
                    foreach(array(0,1,2,3,4,5,6) as $day){
                        $dayName = $weekDays[$day];
                        echo "<div class = 'day'><h2>$dayName</h2><ol>";
                        
                        $res = pg_query($db, "SELECT * FROM mealline WHERE day=$day AND mealid=$mealid;");
                        echo pg_last_error($db);
                        while($row = pg_fetch_assoc($res)){
                            $thisId = $row["recipeid"] ;
                            $result = pg_query($db, "SELECT * FROM recipes WHERE recipeid=$thisId;");
                            $rrow = pg_fetch_assoc($result);
                            //print_r($row);
                            echo "<li>";
                            echo $rrow["recipename"] . "</li>";
                        }
                        echo "</ol></div>";
                    }
                    pg_close($db);
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
