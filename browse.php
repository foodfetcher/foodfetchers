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
        <title> Food Fetchers | Browse </title>
        <link rel="stylesheet" href="phaseIstyle.css">
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id = "Content">
            <form action="browse.php" method="post">
				<table style="width: 100%;">
					<tr>
						<!-- Begin Left (browse) Column -->
						<td style="width: 30%;">
							<table style="width: 100%;">
								<tr>
									<td>
										<h1 style="margin-block-start: 0; margin-block-end: 0.25em;"> Search Recipes </h1>
									</td>
								</tr>
								
								<tr>
									<td style="display: flex;">
										<label for="recipeName" style="flex: 0; white-space: pre; padding-top: 4px;";>Recipe Name:</label>
										<input type="text" id="recipeName" placeholder="steamed hams" style="flex: 1; margin-left: 4px;"><br/>
									</td>
								</tr>
								
								<tr>
									<td style="display: flex;">
										<label for="author" style="flex: 0; white-space: pre; padding-top: 4px;">Author:</label>
										<input type="text" id="author" placeholder="John Smith" style="flex: 1; margin-left: 4px;"><br/>
									</td>
								</tr>
								
								<tr>
									<td style="display: flex;">
										<label for="keywords" style="flex: 0; white-space: pre; padding-top: 4px;">Key Words:</label>
										<input type="text" id="keywords" placeholder="walnuts, ice cream, sugar" style="flex: 1; margin-left: 4px;"><br/>
									</td>
								</tr>
								
								<tr>
									<td style="text-align: left;">
										<h3 style="margin-block-start: 0.5em; margin-block-end: 0.25em;">Dietary Preferences</h3>
										<table>
											<td style="width: 50%">
												<input type="checkbox" id="vegetarian" value="false" name="vegetarianCheck">
												<label for="vegetarian" style="vertical-align: text-bottom;">Vegetarian</label><br/>
												<input type="checkbox" id="vegan" value="false" name="veganCheck">
												<label for="vegan" style="vertical-align: text-bottom;">Vegan</label><br/>
												<input type="checkbox" id="kosher" value="false" name="kosherCheck">
												<label for="kosher" style="vertical-align: text-bottom;">Kosher</label><br/>
												<input type="checkbox" id="nutfree" value="false" name="nutCheck">
												<label for="nutfree" style="vertical-align: text-bottom;">Nut-Free</label><br/>
											</td>
											<td style="width: 50%; padding-left: 5px;">
												<input type="checkbox" id="wheatfree" value="false" name="wheatCheck">
												<label for="wheatfree" style="vertical-align: text-bottom;">Wheat-Free</label><br/>
												<input type="checkbox" id="soyfree" value="false" name="soyCheck">
												<label for="soyfree" style="vertical-align: text-bottom;">Soy-Free</label><br/>
												<input type="checkbox" id="glutenfree" value="false" name="glutenCheck">
												<label for="glutenfree" style="vertical-align: text-bottom;">Gluten-Free</label><br/>
												<input type="checkbox" id="dairyfree" value="false" name="dairyCheck">
												<label for="dairyfree" style="vertical-align: text-bottom;">Dairy-Free</label><br/>
											</td>
										</table>
									</td>
								</tr>
										
								<tr>
									<td style="cursor: default; padding-top: 5px;">
										<input type="submit" value="Search" class="seventh">
										<input type="reset" value="Clear" class="seventh">
									</td>
								</tr>
								<tr>
									<td>
										<?php
											if($log == "Logout"){
												echo '<input type="submit" name="myrecipes" value="View My Recipes" class = "seventh"/>';
											}
										?>
									</td>
								</tr>
							</table>
						</td>
						
						<!-- Begin Right (results) Column -->
						<td style="width: 70%; vertical-align: top; border-left: 1px solid #888; padding-left: 5px;">
							<div id = "results">
								<?php
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
											$recipeName = $_POST['recipeNameName'];
											$authorName = $_POST['authorName'];
											$keywordName = $_POST['keywordName'];
											if ($vegetarian = $_POST['vegetarianCheck'] == "false") //false when it is checked?
											{
												$vegetarian = "on";
												$vegetarian2 = "on";
											}
											else //when it is not checked
											{
												$vegetarian = "off";
												$vegetarian2 = "on";
											}
											
											if ($vegan = $_POST['veganCheck'] == "false") //false when it is checked?
											{
												$vegan = "on";
												$vegan2 = "on";
											}
											else //when it is not checked
											{
												$vegan = "off";
												$vegan2 = "on";
											}
											
											if ($kosher = $_POST['kosherCheck'] == "false") //false when it is checked?
											{
												$kosher = "on";
												$kosher2 = "on";
											}
											else //when it is not checked
											{
												$kosher = "off";
												$kosher2 = "on";
											}
											
											if ($nutfree = $_POST['nutCheck'] == "false") //false when it is checked?
											{
												$nutfree = "on";
												$nutfree2 = "on";
											}
											else //when it is not checked
											{
												$nutfree = "off";
												$nutfree2 = "on";
											}
											
											if ($wheatfree = $_POST['wheatCheck'] == "false") //false when it is checked?
											{
												$wheatfree = "on";
												$wheatfree2 = "on";
											}
											else //when it is not checked
											{
												$wheatfree = "off";
												$wheatfree2 = "on";
											}
											
											if ($soyfree = $_POST['soyCheck'] == "false") //false when it is checked?
											{
												$soyfree = "on";
												$soyfree2 = "on";
											}
											else //when it is not checked
											{
												$soyfree = "off";
												$soyfree2 = "on";
											}
											
											if ($glutenfree = $_POST['glutenCheck'] == "false") //false when it is checked?
											{
												$glutenfree = "on";
												$glutenfree2 = "on";
											}
											else //when it is not checked
											{
												$glutenfree = "off";
												$glutenfree2 = "on";
											}
											
											if ($dairyfree = $_POST['dairyCheck'] == "false") //false when it is checked?
											{
												$dairyfree = "on";
												$dairyfree2 = "on";
											}
											else //when it is not checked
											{
												$dairyfree = "off";
												$dairyfree2 = "on";
											}
											
											//only selects recipes matching the same search (only that for now) the squiggle squiggle star means like case insensitive and the %s around the variable mean anything can be there
											$res = pg_query($db, "SELECT * FROM recipes WHERE recipename ~~* '%$recipeName%' AND ingredients ~~* '%$keywordName%' 
											AND (vegetarian = '$vegetarian' OR vegetarian = '$vegetarian2') 
											AND (vegan = '$vegan' OR vegan = '$vegan2')
											AND (kosher = '$kosher' OR kosher = '$kosher2')
											AND (nutfree = '$nutfree' OR nutfree = '$nutfree2')
											AND (wheatfree = '$wheatfree' OR wheatfree = '$wheatfree2')
											AND (soyfree = '$soyfree' OR soyfree = '$soyfree2')
											AND (glutenfree = '$glutenfree' OR glutenfree = '$glutenfree2')
											AND (dairyfree = '$dairyfree' OR dairyfree = '$dairyfree2')");
											// INNER JOIN customers ON recipes.userid=customers.userid
											// AND (firstname ~~* '$authorName' OR lastname ~~* '$authorName')
											echo '<table width="100%">';
											$count = 0;
											while($row = pg_fetch_assoc($res)){
												$notEmpty = true;
												$recipeid = $row["recipeid"];
												echo '<td width="25%" ><a href="view.php?id=' . $row["recipeid"] . '"><img src="coverimages/' . $recipeid . '" id="resultImage" alt="recipe cover image" width="200px" object-fit:none/></a></br>';
												echo '<a href="view.php?id=' . $row["recipeid"] . '">' . $row['recipename'] . '</a></td>';
												$count += 1;
												if ($count == 4)
												{
													echo '<tr>';
													$count = 0;
												}
											}
											echo '</table>';
											if ($count == 0 && $notEmpty == false)
											{
												echo 'Sorry, no recipes matched those filters. Try widening your search!';
											}					
										}
										pg_close($db);
									}
								?>
							</div>
						</td>
					</tr>
				</table>
            </form>
        </div>
    </body>
</html>
