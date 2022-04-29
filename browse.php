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
						<td style="width: 30%; vertical-align:top;">
							<table style="width: 100%;">
								<tr>
									<td>
										<h1 style="margin-block-start: 0; margin-block-end: 0.25em;"> Search Recipes </h1>
									</td>
								</tr>
								
								<tr>
									<td style="display: flex;">
										<label for="recipeName" style="flex: 0; white-space: pre; padding-top: 4px;";>Recipe Name:</label>
										<input type="text" id="recipeName" placeholder="steamed hams" name="recipeNameName" style="flex: 1; margin-left: 4px;"><br/>
									</td>
								</tr>
								
								<tr>
									<td style="display: flex;">
										<label for="author" style="flex: 0; white-space: pre; padding-top: 4px;">Author:</label>
										<input type="text" id="author" placeholder="John Smith" name="authorName" style="flex: 1; margin-left: 4px;"><br/>
									</td>
								</tr>
								
								<tr>
									<td style="display: flex;">
										<label for="keywords" style="flex: 0; white-space: pre; padding-top: 4px;">Key Words:</label>
										<input type="text" id="keywords" placeholder="walnuts, ice cream, sugar" name="keywordName" style="flex: 1; margin-left: 4px;"><br/>
									</td>
								</tr>
								
								<tr>
									<td style="text-align: left;">
										<h3 style="margin-block-start: 0.5em; margin-block-end: 0.25em;">Dietary Preferences</h3>
										<table style="width: 100%; padding: 0 20px;">
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
										<input type="submit" value="Search" name="searchRecipe">
										<input type="reset" value="Clear">
									</td>
								</tr>
								<tr>
									<td>
										<?php
											if($log == "Logout"){
												echo '<input type="submit" name="myrecipes" value="View My Recipes"/>';
											}
											else
											{
												echo '<input type="submit" name="surprise" value="Surprise Me!"/>';
											}
										?>
									</td>
								</tr>
								<tr>
									<td>
										<?php
											if($log == "Logout"){
												echo '<input type="submit" name="surprise" value="Surprise Me!"/>';
											}
										?>
									</td>
								</tr>
							</table>
						</td>
						
						<!-- Begin Right (results) Column -->
						<td style="width: 70%; vertical-align: top; border-left: 1px solid #888; padding-left: 5px; font-size: 20px; font-family: ubuntu;">
							<div id = "results">
								<?php
									if($_SERVER['REQUEST_METHOD'] === 'POST'){
										$_SESSION["lastQuery"] = $_POST;
										$DB_HOST='localhost';
										$DB_USER='fetcher1';
										$DB_PASS='1234';
										$DB_NAME='main'; 
										$db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
										
										//clicking the view my recipes button
										if(isset($_POST['myrecipes'])){
											echo '<table width="100%">';
											$userid = $_SESSION['userid'];
											$res = pg_query($db, "SELECT * FROM recipes WHERE creatorid='$userid'");
											while($row = pg_fetch_assoc($res)){
												$notEmpty = true;
												$recipeid = $row["recipeid"];
												
												$filename = '/var/www/html/foodFetchers/master/coverimages/' . $recipeid;

												if (file_exists($filename)) {
													echo '<td width="33%" style="vertical-align:top; overflow-wrap: anywhere; padding-bottom: 8px;"><a href="view.php?id=' . $row["recipeid"] . '"><img src="coverimages/' . $recipeid . '" id="resultImage" alt="recipe cover image"/></a></br>';
												} else {
													echo '<td width="33%" style="vertical-align:top; overflow-wrap: anywhere; padding-bottom: 8px;"><a href="view.php?id=' . $row["recipeid"] . '"><img src="coverimages/logo.png" id="resultImage" alt="recipe cover image"/></a></br>';
												}
												
												
												echo '<a href="view.php?id=' . $row["recipeid"] . '">' . $row['recipename'] . '</a></td>';
												$count += 1;
												if ($count == 3)
												{
													echo '<tr>';
													$count = 0;
												}
											}
											echo '</table>';
											if ($count == 0 && $notEmpty == false)
											{
												echo 'Sorry, no recipes matched those filters. Try widening your search.';
											}
										}
										
										//clicking the surprise me or search buttons
										if(isset($_POST['surprise']) or isset($_POST['searchRecipe'])){
											$recipeName = $_POST['recipeNameName'];
											$authorName = $_POST['authorName'];
											$keywordName = $_POST['keywordName'];
											$alwayson = "on";
											$count = 0;
											if ($vegetarian = $_POST['vegetarianCheck'] == "false") //false when it is checked?
											{
												$vegetarian = "on";
											}
											else //when it is not checked
											{
												$vegetarian = "off";
											}
											
											if ($vegan = $_POST['veganCheck'] == "false") //false when it is checked?
											{
												$vegan = "on";
											}
											else //when it is not checked
											{
												$vegan = "off";
											}
											
											if ($kosher = $_POST['kosherCheck'] == "false") //false when it is checked?
											{
												$kosher = "on";
											}
											else //when it is not checked
											{
												$kosher = "off";
											}
											
											if ($nutfree = $_POST['nutCheck'] == "false") //false when it is checked?
											{
												$nutfree = "on";
											}
											else //when it is not checked
											{
												$nutfree = "off";
											}
											
											if ($wheatfree = $_POST['wheatCheck'] == "false") //false when it is checked?
											{
												$wheatfree = "on";
											}
											else //when it is not checked
											{
												$wheatfree = "off";
											}
											
											if ($soyfree = $_POST['soyCheck'] == "false") //false when it is checked?
											{
												$soyfree = "on";
											}
											else //when it is not checked
											{
												$soyfree = "off";
											}
											
											if ($glutenfree = $_POST['glutenCheck'] == "false") //false when it is checked?
											{
												$glutenfree = "on";
											}
											else //when it is not checked
											{
												$glutenfree = "off";
											}
											
											if ($dairyfree = $_POST['dairyCheck'] == "false") //false when it is checked?
											{
												$dairyfree = "on";
											}
											else //when it is not checked
											{
												$dairyfree = "off";
											}
											
											
											$res = pg_query_params($db, "SELECT * FROM recipes INNER JOIN customers ON recipes.creatorid=customers.userid WHERE recipename ~~* $1 AND ingredients ~~* $2
											AND (vegetarian = $3 OR vegetarian = $4)
											AND (vegan = $5 OR vegan = $4)
											AND (kosher = $6 OR kosher = $4)
											AND (nutfree = $7 OR nutfree = $4)
											AND (wheatfree = $8 OR wheatfree = $4)
											AND (soyfree = $9 OR soyfree = $4)
											AND (glutenfree = $10 OR glutenfree = $4)
											AND (dairyfree = $11 OR dairyfree = $4)
											AND (firstname ~~* $12 OR lastname ~~* $12)",
											array("%" . $recipeName . "%", "%" . $keywordName . "%", $vegetarian, $alwayson, $vegan, $kosher, $nutfree, $wheatfree, $soyfree, $glutenfree, $dairyfree, "%" . $authorName . "%"));
											
											// clicking the surprise me button
											if(isset($_POST['surprise'])){
												$resultArray=array();
												while($row = pg_fetch_assoc($res)){
													$notEmpty = true;
													$recipeid = $row["recipeid"];
													array_push($resultArray, $recipeid);
													$count += 1;
												}
												if ($count > 0){
													$rand = $resultArray[array_rand($resultArray)];
													echo "<script> location.href='/view.php?id=$rand'; </script>";
													exit;
												}
												if ($count == 0 && $notEmpty == false)
												{
													echo 'Sorry, no recipes matched those filters. Try widening your search.';
												}
											}
											
											// clicking the search button
											if(isset($_POST['searchRecipe'])){
												echo '<table width="100%">';
												while($row = pg_fetch_assoc($res)){
													$notEmpty = true;
													$recipeid = $row["recipeid"];
													
													$filename = '/var/www/html/foodFetchers/master/coverimages/' . $recipeid;

													if (file_exists($filename)) {
														echo '<td width="33%" style="vertical-align:top; overflow-wrap: anywhere; padding-bottom: 8px;"><a href="view.php?id=' . $row["recipeid"] . '"><img src="coverimages/' . $recipeid . '" id="resultImage" alt="recipe cover image"/></a></br>';
													} else {
														echo '<td width="33%" style="vertical-align:top; overflow-wrap: anywhere; padding-bottom: 8px;"><a href="view.php?id=' . $row["recipeid"] . '"><img src="coverimages/logo.png" id="resultImage" alt="recipe cover image"/></a></br>';
													}
													
													echo '<a href="view.php?id=' . $row["recipeid"] . '">' . $row['recipename'] . '</a></td>';
													$count += 1;
													if ($count == 3)
													{
														echo '<tr>';
														$count = 0;
													}
												}
												echo '</table>';
												if ($count == 0 && $notEmpty == false)
												{
													echo 'Sorry, no recipes matched those filters. Try widening your search.';
												}
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
