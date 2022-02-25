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
        <title> Food Fetchers | Create Recipe </title>
        <link rel="stylesheet" href="phaseIstyle.css">
		<?php
			//echo "here!";
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				//print_r($_POST);
				
				$recipeName = $_POST["recipeName"];
				$ingredients = $_POST["ingredients"];
				$instructions = $_POST["instructions"];
				$vegetarian = filter_var($_POST["vegetarian"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false"; 
				$vegan = filter_var($_POST["vegan"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				$kosher = filter_var($_POST["kosher"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				$nutfree = filter_var($_POST["nutfree"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				$wheatfree = filter_var($_POST["wheatfree"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				$soyfree = filter_var($_POST["soyfree"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				$glutenfree = filter_var($_POST["glutenfree"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				$dairyfree = filter_var($_POST["dairyfree"], FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
				
				$coverImage = $_FILES['coverimage'];
				
				//TODO: make sure to validate and sanitize these fields
				
				$userid = $_SESSION["userid"];
				$timestamp = date('Y-m-d H:i:s');
				include 'DButils.php';
				$db = getDefaultDB();
				$res = pg_query_params($db, "INSERT INTO recipes (recipename, ingredients, instructions, creatorid, creationdate, vegetarian, vegan, kosher, nutfree, wheatfree, soyfree, glutenfree, dairyfree) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13) RETURNING recipeid", array($recipeName, $ingredients, $instructions, $userid, $timestamp, 
				
				$vegetarian, $vegan, $kosher, $nutfree, $wheatfree, $soyfree, $glutenfree, $dairyfree));
				if($res === false){
					$outcome = pg_last_error($db) . $vegetarian;
				}
				else{
					$recipeid = pg_fetch_assoc($res)["recipeid"];
					if($coverImage["tmp_name"]){
						if(move_uploaded_file($coverImage["tmp_name"], "coverimages/$recipeid")){
							$outcome = "success";
						}
						else{
							$outcome = "could not move uploaded file (backend error)<br/>tmp_name: " . $coverImage["tmp_name"];
						}
					}
					else{
						$outcome = "success";
					}
				}
				
				//print_r($coverImage);
				
				pg_close($db);
			}
		?>
	</head>
    <body>
		<div id="background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
		?> 
        <div id = "Content">
		<h1> Create your own recipe </h1>
		<form action="create.php" method="post" enctype="multipart/form-data">
			<label for="recipeName">Recipe name:</label>
			<input type="text" name="recipeName" placeholder="steamed hams" value="<?php echo $recipeName;?>" required><br/>
			
			<label for="coverimage" value="<?php echo $coverImage;?>">Cover Image:</label>
			<input type="file" name="coverimage" accept=".png, .jpeg, .jpg"><br/>
			
			<label for="ingredients">Ingredients</label>
		<input type="text" name="ingredients" placeholder="walnuts, soy sauce, cinnamon" value="<?php echo $ingredients;?>" required><br/>
		
		<label for="instructions">Instructions</label><br>
		<textarea name="instructions" rows="12" cols="80" placeholder="describe how to make your recipe! You can even use html tags and image links to spice things up. Treat it like a blog post! (just don't be evil with those tags)" required><?php echo $instructions;?></textarea><br/>
		<p>Declare your recipe as:</p>
		
		<label for="vegetarian">Vegetarian</label>
		<input type="checkbox" name="vegetarian" <?php if($vegetarian=="true"){echo "checked";} ?>><br/>
		<label for="vegan">Vegan</label>
		<input type="checkbox" name="vegan" <?php if($vegan=="true"){echo "checked";} ?>><br/>
		<label for="kosher">Kosher</label>
		<input type="checkbox" name="kosher" <?php if($kosher=="true"){echo "checked";} ?>><br/>
		<label for="nutfree">Nut-Free</label>
		<input type="checkbox" name="nutfree" <?php if($nutfree=="true"){echo "checked";} ?>><br/>
		<label for="wheatfree">Wheat-Free</label>
		<input type="checkbox" name="wheatfree" <?php if($wheatfree=="true"){echo "checked";} ?>><br/>
		<label for="soyfree">Soy-Free</label>
		<input type="checkbox" name="soyfree" <?php if($soyfree=="true"){echo "checked";} ?>><br/>
		<label for="glutenfree">Gluten-Free</label>
		<input type="checkbox" name="glutenfree" <?php if($glutenfree=="true"){echo "checked";} ?>><br/>
		<label for="dairyfree">Dairy-Free</label>
		<input type="checkbox" name="dairyfree" <?php if($dairyfree=="true"){echo "checked";} ?>><br/>
		
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
