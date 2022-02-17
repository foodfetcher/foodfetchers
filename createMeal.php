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
        <title> Food Fetchers | create Meal </title>
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
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <h1> Create a meal plan </h1>
            <?php
                
            ?>
            <form method="post">
                <label for="mealname">Meal name:</label>
                <input type="text" name="mealname" placeholder="steamed hams on all days" required><br/>
                
                <p> this stuff will be filled out using javascript and click and drag--the user will not need to manually enter in meal ids </p>
                <p> for now, enter the recipe ids you want on each day seperated by spaces, as shown in the placeholder text. Any previously favorited recipes are shown below the form. </p>
                <label for="sunday">Sunday:</label>
                <input type="text" name="sunday" placeholder="1 2 3 4" required><br/>
                <label for="monday">Monday:</label>
                <input type="text" name="monday" placeholder="1 2 3 4" required><br/>
                <label for="tuesday">Tuesday:</label>
                <input type="text" name="tuesday" placeholder="1 2 3 4" required><br/>
                <label for="wednesday">Wednesday:</label>
                <input type="text" name="wednesday" placeholder="1 2 3 4" required><br/>
                <label for="thursday">Thursday:</label>
                <input type="text" name="thursday" placeholder="1 2 3 4" required><br/>
                <label for="friday">Friday:</label>
                <input type="text" name="friday" placeholder="1 2 3 4" required><br/>
                <label for="saturday">Saturday:</label>
                <input type="text" name="saturday" placeholder="1 2 3 4" required><br/>
                
                <input type="submit" value = "Submit" class = "seventh">
                <input type="reset" value = "Clear" class = "seventh">
            </form>
            
            <div id = "results">
                <?php
                    if($_SERVER['REQUEST_METHOD'] === 'GET'){
                        $_SESSION["lastQuery"] = $_POST;
                        $DB_HOST='localhost';
                        $DB_USER='fetcher1';
                        $DB_PASS='1234';
                        $DB_NAME='main'; 
                        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                        //echo "myrecipes";
                        $userid = $_SESSION['userid'];
                        $res = pg_query($db, "SELECT * FROM favorites
                        INNER JOIN recipes ON favorites.recipeid=recipes.recipeid 
                        WHERE favorites.userid='$userid';");
                        echo pg_last_error($db);
                        echo "<p>Your favorited recipes:</p><br/>";
                        echo "<table id=restable><th>recipe name</th><th>recipe id</th>";
                        while($row = pg_fetch_assoc($res)){
                            echo "<tr><td>" . $row['recipename'] . "</td><td>" . $row['recipeid'] . "</td></tr>";
                        }
                        echo "</table>";
                        pg_close($db);
                        
                    }
                    else if($_SERVER['REQUEST_METHOD'] === 'POST'){
                        $_SESSION["lastQuery"] = $_POST;
                        $DB_HOST='localhost';
                        $DB_USER='fetcher1';
                        $DB_PASS='1234';
                        $DB_NAME='main'; 
                        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                        //echo "myrecipes";
                        $userid = $_SESSION['userid'];
                        $mealname = $_POST["mealname"];
                        $timestamp = date('Y-m-d H:i:s');
                        
                        $mealid = pg_fetch_assoc(pg_query($db, "INSERT INTO meals (mealname, customerid, creationdate) VALUES ('$mealname', $userid, '$timestamp') RETURNING mealid;"))["mealid"];
                        
                        echo pg_last_error($db) . "<br/>";
                        $week = array(explode(" ", $_POST["sunday"], PHP_INT_MAX),
                        explode(" ", $_POST["monday"], PHP_INT_MAX),
                        explode(" ", $_POST["tuesday"], PHP_INT_MAX),
                        explode(" ", $_POST["wednesday"], PHP_INT_MAX),
                        explode(" ", $_POST["thursday"], PHP_INT_MAX),
                        explode(" ", $_POST["friday"], PHP_INT_MAX),
                        explode(" ", $_POST["saturday"], PHP_INT_MAX));
                        
                        foreach($week as $day=>$arr){
                            foreach($arr as $item){
                                pg_query($db, "INSERT INTO mealline (mealid, recipeid, day) VALUES ($mealid, $item, $day);");
                                echo pg_last_error($db) . "<br/>";
                                echo "inserted $item to $day <br/>";
                            }
                        }
                        
                        pg_close($db);
                    }
                ?>
            </div>
        </div>
    </body>
</html>
