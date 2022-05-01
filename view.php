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

    $recipeid = (int)$_GET["id"] ?? (int)$_POST["id"];
    $queryResultRow = Array();
    $creatorInfo = Array();
    include "DButils.php";
	$db = getDefaultDB();

    $res = pg_query_params($db, "SELECT * FROM recipes WHERE recipeid=$1", Array($recipeid));
    if(pg_num_rows($res) == 0){
        $invalidRecipe = true;
    }
    else{
        $queryResultRow = pg_fetch_assoc($res);
        $creatorid = $queryResultRow['creatorid'];
        $res = pg_query_params($db, "SELECT username FROM customers WHERE userid=$1", Array($creatorid));
        if(pg_num_rows($res) == 0){

            $creatorInfo['username'] = "Recipe creator could not be found.";
        }
        else{
            $row = pg_fetch_assoc($res);
            //print_r($row);
            $creatorInfo['username'] = $row['username'];
        }
    }


    if(isset($_POST["favorite"])){
        $userid = $_SESSION["userid"];
        $res = pg_query_params($db, "INSERT INTO favorites (recipeid, userid) values ($1,$2);", Array($recipeid, $userid));
        echo pg_last_error($db);
    }
	else if(isset($_POST["unfavorite"])){
		$userid = $_SESSION["userid"];
        $res = pg_query_params($db, "DELETE FROM favorites WHERE recipeid=$1 AND userid=$2;", Array($recipeid, $userid));
        echo pg_last_error($db);
	}


?>
<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | <?php echo $queryResultRow["recipename"]; ?> </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <script>
            window.onpopstate = function() {
                window.location.href = "browse.php?useLastQuery=true";
            }
        </script>
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?>
        <div id = "Content">
            <h1 style="margin-block-end: 0.2em;"> <?php echo $queryResultRow["recipename"]; ?> </h1>
			<?php echo "<p>Created by " . $creatorInfo['username'] . "</p>"; ?>
            <div id = "results">
                <?php
                    if(isset($invalidRecipe)){
                        echo "<h1>Unknown Recipe</h1>";
                    }
                    else{
                        echo "<table style='width: 100%;'><tbody style='vertical-align: top;'><td style='width: 45%;'>";
						$filename = '/var/www/html/foodFetchers/master/coverimages/' . $recipeid;
						if (file_exists($filename)) {
							echo '<img src="coverimages/' . $recipeid . '" alt="recipe cover image exists" style="width: 100%; box-shadow: 0 0 3px gray;"/>';
						}
						else {
							echo '<img src="coverimages/logo.png" alt="recipe cover image does not exist" style="width: 100%; box-shadow: 0 0 3px gray;"/>';
						}

                        echo "</td><td style='padding-left: 5px;'>";

                        /*echo "<p>At: " . $queryResultRow['creationdate'] . "</p>";*/
                        echo "<h3>Ingredients</h3><p>";
                        $groceryList =  showIngredients($queryResultRow['ingredients'],$db);
                        foreach($groceryList as $ingredient => $quantity){
                            echo  $quantity[0] . ' '. $quantity[1] . ' ' . $ingredient . "<br>"; 
                        }
                        echo "<h3>Instructions</h3><p>" . str_replace("\n", "<br/><br/>", $queryResultRow['instructions']) . "</p>";
                        echo "<h3>Dietary Information</h3>";
                        foreach(Array("vegetarian","vegan","kosher","nutfree","wheatfree","soyfree","glutenfree","dairyfree") as $diet){
							if($queryResultRow[$diet] == "t"){
								if($diet == "vegetarian"){
									echo "✓&nbsp;Vegetarian&nbsp; ";
								}
								if($diet == "vegan"){
									echo "✓&nbsp;Vegan&nbsp; ";
								}
								if($diet == "kosher"){
									echo "✓&nbsp;Kosher&nbsp; ";
								}
								if($diet == "nutfree"){
									echo "✓&nbsp;Nut&#8209;Free&nbsp; ";
								}
								if($diet == "wheatfree"){
									echo "✓&nbsp;Wheat&#8209;Free&nbsp; ";
								}
								if($diet == "soyfree"){
									echo "✓&nbsp;Soy&#8209;Free&nbsp; ";
								}
								if($diet == "glutenfree"){
									echo "✓&nbsp;Gluten&#8209;Free&nbsp; ";
								}
								if($diet == "dairyfree"){
									echo "✓&nbsp;Dairy&#8209;Free&nbsp; ";
								}
							}
                        }

                        echo "</td></tbody></table>";
                        if(isset($_SESSION["userid"])){
							$userid = $_SESSION["userid"];
							$res = pg_query_params($db, "SELECT * FROM favorites WHERE recipeid=$1 AND userid=$2;", Array($recipeid, $userid));
							if(pg_num_rows($res) != 0){
								echo '<form method="post">';
								echo '<input name="unfavorite" type="hidden" value="yes">';
								echo '<input name="id" type="hidden" value="' . $recipeid . '">';
								echo '<input type="submit" value = "Remove from favorites">';
								echo "</form>";
							}
							else{
								echo '<form method="post">';
								echo '<input name="favorite" type="hidden" value="yes">';
								echo '<input name="id" type="hidden" value="' . $recipeid . '">';
								echo '<input type="submit" value = "Add to favorites">';
								echo "</form>";
							}

							$res = pg_query_params($db, "SELECT * FROM recipes WHERE recipeid=$1 AND creatorid=$2;", Array($recipeid, $userid));
							//echo $recipeid . $userid . " " . pg_last_error($db);
							if(pg_num_rows($res) != 0){
								echo "<a href='create.php?recipeid=$recipeid'><button>edit this recipe</button></a>";
							}
                        }
                    }
					pg_close($db);
                ?>
            </div>
        </div>
    </body>
</html>
