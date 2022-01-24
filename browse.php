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
        <title> Food Fetchers | browse </title>
        <link rel="stylesheet" href="phaseIstyle.css">
    </head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <h1> Browse Recipes </h1>
            <form action="browse.php" method="post">
                <label for="recipeName">Recipe name:</label>
                <input type="text" id="recipeName" placeholder="steamed hams"><br/>
                
                <label for="author">Author:</label>
                <input type="text" id="author" placeholder="John Smith"><br/>
                
                <label for="keywords">Key words:</label>
                <input type="text" id="keywords" placeholder="walnuts, icecream, sugar"><br/>
                
                <p>Dietary preferences</p>
                <label for="vegetarian">Vegetarian</label>
                <input type="checkbox" id="vegetarian" value="false"><br/>
                <label for="vegan">Vegan</label>
                <input type="checkbox" id="vegan" value="false"><br/>
                <label for="kosher">Kosher</label>
                <input type="checkbox" id="kosher" value="false"><br/>
                <label for="nutfree">Nut-Free</label>
                <input type="checkbox" id="nutfree" value="false"><br/>
                <label for="wheatfree">Wheat-Free</label>
                <input type="checkbox" id="wheatfree" value="false"><br/>
                <label for="soyfree">Soy-Free</label>
                <input type="checkbox" id="soyfree" value="false"><br/>
                <label for="glutenfree">Gluten-Free</label>
                <input type="checkbox" id="glutenfree" value="false"><br/>
                <label for="dairyfree">Dairy-Free</label>
                <input type="checkbox" id="dairyfree" value="false"><br/>
                
                <input type="submit" value = "Submit" class = "seventh">
                <?php
                    if($log == "Logout"){
                        echo '<input type="submit" name="myrecipes" value="View My Recipes" class = "seventh"/>';
                    }
                ?>
                <input type="reset" value = "Clear" class = "seventh">
            </form>
            
            <div id = "results">
                <h3>results get formatted here in a grid with links and nice things. For now, simple links to view pages for each recipe are displayed. It would be nice to have a default query or something here that shows the latest recipes by default</h3>
                <?php
					$table .= "<table style ='margin-left: auto; margin-right: auto'; border=\"1\";>";
                    if($_SERVER['REQUEST_METHOD'] === 'POST'){
                        $_SESSION["lastQuery"] = $_POST;
                        $DB_HOST='localhost';
                        $DB_USER='fetcher1';
                        $DB_PASS='1234';
                        $DB_NAME='main'; 
                        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                        if(isset($_POST['myrecipes'])){
                            //echo "myrecipes";
                            $userid = $_SESSION['userid'];
                            $res = pg_query($db, "SELECT * FROM recipes WHERE creatorid='$userid'");
                            while($row = pg_fetch_assoc($res)){
                                echo '<a href="view.php?id=' . $row["recipeid"] . '">' . $row['recipename'] . '</a></br>';
                            }
                        }
                        else{
                            $res = pg_query($db, "SELECT * FROM recipes");
                            while($row = pg_fetch_assoc($res)){
								//$table .= '<tr><a href="view.php?id=' . $row["recipeid"] . '">' . $row['recipename'] . '</a></tr>';
                                echo '<a href="view.php?id=' . $row["recipeid"] . '">' . $row['recipename'] . '</a></br>';
								//echo $table;
                            }
                        }
                        pg_close($db);
                        
                    }
                ?>
            </div>
        </div>
    </body>
</html>
