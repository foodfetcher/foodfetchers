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
    
    $res = pg_query($db, "SELECT * FROM recipes WHERE recipeid='$recipeid'");
    if(pg_num_rows($res) == 0){
        $invalidRecipe = true;
    }
    else{
        $queryResultRow = pg_fetch_assoc($res);
        $creatorid = $queryResultRow['creatorid'];
        $res = pg_query($db, "SELECT firstname, lastname FROM customers WHERE userid=$creatorid");
        if(pg_num_rows($res) == 0){
            
            $creatorInfo['firstname'] = "creator";
            $creatorInfo['lastname'] = "could not be found";
        }
        else{
            $row = pg_fetch_assoc($res);
            //print_r($row);
            $creatorInfo['firstname'] = $row['firstname'];
            $creatorInfo['lastname'] = $row['lastname'];
        }
    }
    
    
    if(isset($_POST["favorite"])){
        $userid = $_SESSION["userid"];
        $res = pg_query($db, "INSERT INTO favorites (recipeid, userid) values ($recipeid, $userid);");
        echo pg_last_error($db);
    }
	else if(isset($_POST["unfavorite"])){
		$userid = $_SESSION["userid"];
        $res = pg_query($db, "DELETE FROM favorites WHERE recipeid='$recipeid' AND userid='$userid';");
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
            <h1> <?php echo $queryResultRow["recipename"]; ?> </h1>
            <div id = "results">
                <?php
                    if(isset($invalidRecipe)){
                        echo "<h1>Unknown Recipe!</h1>";
                    }
                    else{
                        echo "<table><tbody style='vertical-align: top;'><td style='width: 50%;'>";
						            $filename = '/var/www/html/foodFetchers/master/coverimages/' . $recipeid;
						            if (file_exists($filename)) {
							              echo '<img src="coverimages/' . $recipeid . '" alt="recipe cover image exists" style="width: 100%;"/>';
						            } else {
							              echo '<img src="coverimages/logo.png" alt="recipe cover image does not exist" style="width: 100%;"/>';
						            }
                        
                        echo "</td><td style='padding-left: 5px;'>";
                        echo "<p>Created by: " . $creatorInfo['firstname'] . " " . $creatorInfo['lastname'] . "</p>";
                        echo "<p>At: " . $queryResultRow['creationdate'] . "</p>";
                        echo "<h3>Ingredients:</h3><p>" . $queryResultRow['ingredients'] . "</p>";
                        echo "<h3>Instructions:</h3><p>" . str_replace("\n", "<br/>", $queryResultRow['instructions']) . "</p>";
                        echo "</td></tbody></table>";
                        if(isset($_SESSION["userid"])){
							$userid = $_SESSION["userid"];
							$res = pg_query($db, "SELECT * FROM favorites WHERE recipeid='$recipeid' AND userid='$userid';");
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
                        }
                    }
					pg_close($db);
                ?>
            </div>
        </div>
    </body>
</html>
