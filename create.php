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
        <title> Food Fetchers | create </title>
        <link rel="stylesheet" href="phaseIstyle.css">
		<?php
			//echo "here!";
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				//print_r($_POST);
				
				$recipeName = $_POST["recipeName"];
				$ingredients = $_POST["ingredients"];
				$instructions = $_POST["instructions"];
				$vegetarian = $_POST["vegetarian"] ?? "off";
				$vegan = $_POST["vegan"] ?? "off";
				$kosher = $_POST["kosher"] ?? "off";
				$nutfree = $_POST["nutfree"] ?? "off";
				$wheatfree = $_POST["wheatfree"] ?? "off";
				$soyfree = $_POST["soyfree"] ?? "off";
				$glutenfree = $_POST["glutenfree"] ?? "off";
				$dairyfree = $_POST["dairyfree"] ?? "off";
				
				$coverImage = $_FILES['coverimage'];
				
				//TODO: make sure to validate and sanitize these fields
				
				$userid = $_SESSION["userid"];
				$timestamp = date('Y-m-d H:i:s');
				
				$db = getDefaultDB();
				$res = pg_query_params($db, "INSERT INTO recipes (recipename, ingredients, instructions, creatorid, creationdate, vegetarian, vegan, kosher, nutfree, wheatfree, soyfree, glutenfree, dairyfree) VALUES ('$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10', '$11', '$12', '$13') RETURNING recipeid", $recipeName, $ingredients, $instructions, $userid, $timestamp, $vegetarian, $vegan, $kosher, $nutfree, $wheatfree, $soyfree, $glutenfree, $dairyfree);
				if($res === false){
					$outcome = pg_last_error($db);
				}
				else{
					$recipeid = pg_fetch_assoc($res)["recipeid"];
					if( move_uploaded_file($coverImage["tmp_name"], "coverimages/$recipeid")){
						$outcome = "success";
					}
					else{
						$outcome = "could not move uploaded file (backend error)";
					}
				}
				
				//print_r($coverImage);
				
				pg_close($db);
			}
		?>
	</head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
		?> 
        <div id = "Content">
            <h1> Create your own recipe </h1>
            <?php
                
			?>
            <form action="create.php" method="post" enctype="multipart/form-data">
                <label for="recipeName">Recipe name:</label>
                <input type="text" name="recipeName" placeholder="steamed hams" value="<?php echo $recipeName;?>" required><br/>
                
                <label for="coverimage" value="<?php echo $coverImage;?>">Cover Image:</label>
                <input type="file" name="coverimage" accept=".png, .jpeg, .jpg"><br/>
                
                <label for="ingredients">Ingredients</label>
				<input type="text" name="ingredients" placeholder="walnuts, soy sauce, cinnamon" value="<?php echo $ingredients;?>" required><br/>
				
				<label for="instructions">Instructions</label><br>
				<textarea name="instructions" <?php echo $instructions;?> rows="12" cols="80" placeholder="describe how to make your recipe! You can even use html tags and image links to spice things up. Treat it like a blog post! (just don't be evil with those tags)" required></textarea><br/>
				<p>Declare your recipe as:</p>
				
				<label for="vegetarian">Vegetarian</label>
				<input type="checkbox" name="vegetarian" value="<?php echo $vegetarian;?>"><br/>
				<label for="vegan">Vegan</label>
				<input type="checkbox" name="vegan" value="<?php echo $vegan;?>"><br/>
				<label for="kosher">Kosher</label>
				<input type="checkbox" name="kosher" value="<?php echo $kosher;?>"><br/>
				<label for="nutfree">Nut-Free</label>
				<input type="checkbox" name="nutfree" value="<?php echo $nutfree;?>"><br/>
				<label for="wheatfree">Wheat-Free</label>
				<input type="checkbox" name="wheatfree" value="<?php echo $wheatfree?>"><br/>
				<label for="soyfree">Soy-Free</label>
				<input type="checkbox" name="soyfree" value="<?php echo $soyfree;?>"><br/>
				<label for="glutenfree">Gluten-Free</label>
				<input type="checkbox" name="glutenfree" value="<?php echo $glutenfree;?>"><br/>
				<label for="dairyfree">Dairy-Free</label>
				<input type="checkbox" name="dairyfree" value="<?php echo $dairyfree?>"><br/>
				
				<input type="submit" value = "Submit" class = "seventh">
				<input type="reset" value = "Clear" class = "seventh">
			</form>
            
            <div id = "results">
				<?php 
					echo $outcome;
				?>
			</div>
		</div>
	</body>
</html>
