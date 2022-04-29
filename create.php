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
			include 'DButils.php';
			//echo "here!";
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				//print_r($_POST);
				$recipeid = $_POST["recipeid"];
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
				$db = getDefaultDB();
				if(isset($recipeid)){
					$res = pg_query_params($db, "SELECT recipeid FROM recipes WHERE recipeid=$1 AND creatorid=$2;", Array($recipeid, $userid));
					if(pg_num_rows($res) != 0){
						if(isset($_POST["delete"])){
							pg_query_params($db, "DELETE FROM recipes WHERE recipeid=$1;", Array($recipeid));
						}
						else{
						$res = pg_query_params($db, "INSERT INTO recipes (recipeid, recipename, ingredients, instructions, creatorid, creationdate, vegetarian, vegan, kosher, nutfree, wheatfree, soyfree, glutenfree, dairyfree) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14) RETURNING recipeid", array($recipeid, $recipeName, $ingredients, $instructions, $userid, $timestamp, $vegetarian, $vegan, $kosher, $nutfree, $wheatfree, $soyfree, $glutenfree, $dairyfree));
						}
					}
					else{
						$outcome = "You do not have permission to edit this recipe";
					}
				}
				else{
					$res = pg_query_params($db, "INSERT INTO recipes (recipename, ingredients, instructions, creatorid, creationdate, vegetarian, vegan, kosher, nutfree, wheatfree, soyfree, glutenfree, dairyfree) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13) RETURNING recipeid", array($recipeName, $ingredients, $instructions, $userid, $timestamp, $vegetarian, $vegan, $kosher, $nutfree, $wheatfree, $soyfree, $glutenfree, $dairyfree));
				}
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
			else if($_SERVER["REQUEST_METHOD"] == "GET"){
				$recipeid = $_GET["recipeid"];
				$userid = $_SESSION["userid"];
				$db = getDefaultDB();
				if(isset($recipeid)){
					$res = pg_query_params($db, "SELECT * FROM recipes WHERE recipeid = $1 AND creatorid = $2;", Array($recipeid, $userid));
					if(!isset($userid) || pg_num_rows($res) == 0){
						http_response_code(403);
						die("You do not have permission to edit this recipe!" . pg_num_rows($res));
					}
					$recipeInfo = pg_fetch_assoc($res);
					$recipeName = $recipeInfo["recipename"];
					$ingredients = json_decode($recipeInfo["ingredients"]);
					$instructions = $recipeInfo["instructions"];
					$vegetarian = $recipeInfo["vegetarian"] == 't' ? "true" : "false";
					$vegan = $recipeInfo["vegan"] == 't' ? "true" : "false";
					$kosher = $recipeInfo["kosher"] == 't' ? "true" : "false";
					$nutfree = $recipeInfo["nutfree"] == 't' ? "true" : "false";
					$wheatfree = $recipeInfo["wheatfree"] == 't' ? "true" : "false";
					$soyfree = $recipeInfo["soyfree"] == 't' ? "true" : "false";
					$glutenfree = $recipeInfo["glutenfree"] == 't' ? "true" : "false";
					$dairyfree = $recipeInfo["dairyfree"] == 't' ? "true" : "false";
					//var_dump($vegetarian);

					echo "<script>var ingredients = '$ingredients'</script>";
				}
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
		<h1> <?php if(isset($recipeName)){echo "Editing '" . $recipeName . "'";}else{echo "Create your own recipe";} ?> </h1>
			<table style="width: 100%">
				<form action="create.php" method="post" enctype="multipart/form-data">
				<tr>
					<td style="width: 30%; vertical-align: top;">
						<table style="width: 100%">
							<tr>
								<td style="display: flex;">
									<label for="recipeName" style="flex: 0; white-space: pre; padding-top: 4px;">Recipe Name:</label>
									<input type="text" name="recipeName" placeholder="steamed hams" style="flex: 1; margin-left: 4px;" value="<?php echo $recipeName;?>" required><br/>
								</td>
							</tr>
							<tr>
								<td style="display: flex;">
									<label for="coverimage" style="flex: 0; white-space: pre; padding-top: 2px;">Recipe Image:</label>
									<input type="file" name="coverimage" accept=".png, .jpeg, .jpg" style="flex: 1; margin-left: 4px; width: 100%;"><br/>
								</td>
							</tr>
							<tr>
								<td>
									<div id="ingredientsTable">
										<input type='number' name='amount' style="width: 32px; margin-right: 4px;">
										<select name='unit'>
											<option value='teaspoon'>tsp</option>
											<option value='tablespoon'>tbsp</option>
											<option value='floz'>fl oz</option>
											<option value='oz'>oz</option>
											<option value='cup'>cup</option>
											<option value='pint'>pint</option>
											<option value='quart'>quart</option>
											<option value='ml'>ml</option>
											<option value='liter'>l</option>
											<option value='g'>g</option>
										</select>
										<input type='text' name='ingredient' placeholder='ingredient' style="width:100%; margin-left: 4px;"><br/>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<button style="width: 100%">Add More Ingredients</button>
									<input type='hidden' value='placeholder json'/>
								</td>
							</tr>
							<tr>
								<td style="width: 100%; display: flex;"><p style="width: 100%; text-align: center; font-weight: bold; margin-block-end: 0;">Specify your recipe as:</p></td>
							</tr>
							<tr>
								<td style="text-align: left;">
									<table style="width: 100%; padding: 0 20px;">
										<td style="width: 50%;">
											<?php if(isset($recipeid)){ echo('<input type="hidden" name="recipeid" value="' . $recipeid . '">');}?>
											<input type="checkbox" name="vegetarian" <?php if($vegetarian=="true"){echo "checked";} ?>>
											<label for="vegetarian">Vegetarian</label><br/>
											<input type="checkbox" name="vegan" <?php if($vegan=="true"){echo "checked";} ?>>
											<label for="vegan">Vegan</label><br/>
											<input type="checkbox" name="kosher" <?php if($kosher=="true"){echo "checked";} ?>>
											<label for="kosher">Kosher</label><br/>
											<input type="checkbox" name="nutfree" <?php if($nutfree=="true"){echo "checked";} ?>>
											<label for="nutfree">Nut-Free</label>
										</td>
										<td style="width: 50%; padding-left: 5px;">
											<input type="checkbox" name="wheatfree" <?php if($wheatfree=="true"){echo "checked";} ?>>
											<label for="wheatfree">Wheat-Free</label><br/>
											<input type="checkbox" name="soyfree" <?php if($soyfree=="true"){echo "checked";} ?>>
											<label for="soyfree">Soy-Free</label><br/>
											<input type="checkbox" name="glutenfree" <?php if($glutenfree=="true"){echo "checked";} ?>>
											<label for="glutenfree">Gluten-Free</label><br/>
											<input type="checkbox" name="dairyfree" <?php if($dairyfree=="true"){echo "checked";} ?>>
											<label for="dairyfree">Dairy-Free</label>
										</td>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td style="width: 70%; vertical-align: top; border-left: 1px solid #888; padding-left: 5px;">
						<label for="instructions">Instructions</label><br>
						<textarea name="instructions" rows="14" cols="90" style="resize: vertical;" placeholder="describe how to make your recipe! You can even use html tags and image links to spice things up. Treat it like a blog post! (just don't be evil with those tags)" required><?php echo $instructions;?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="submit" value = "Submit">
						<input type="reset" value = "Clear">
					<td>
				</tr>
				</form>
			</table>
			<?php
			if(isset($recipeid)){
				include 'deleteRecipe.php';//include delete modal
			}
			?>

			<div id = "results">
				<?php
					echo $outcome;
				?>
			</div>
		</div>
	</body>
</html>
