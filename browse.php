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
										<h1 style="margin-block-start: 0; margin-block-end: 0.25em;"> Browse Recipes </h1>
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
										<input type="checkbox" id="vegetarian" value="false">
										<label for="vegetarian" style="vertical-align: text-bottom;">Vegetarian</label><br/>
										<input type="checkbox" id="vegan" value="false">
										<label for="vegan" style="vertical-align: text-bottom;">Vegan</label><br/>
										<input type="checkbox" id="kosher" value="false">
										<label for="kosher" style="vertical-align: text-bottom;">Kosher</label><br/>
										<input type="checkbox" id="nutfree" value="false">
										<label for="nutfree" style="vertical-align: text-bottom;">Nut-Free</label><br/>
										<input type="checkbox" id="wheatfree" value="false">
										<label for="wheatfree" style="vertical-align: text-bottom;">Wheat-Free</label><br/>
										<input type="checkbox" id="soyfree" value="false">
										<label for="soyfree" style="vertical-align: text-bottom;">Soy-Free</label><br/>
										<input type="checkbox" id="glutenfree" value="false">
										<label for="glutenfree" style="vertical-align: text-bottom;">Gluten-Free</label><br/>
										<input type="checkbox" id="dairyfree" value="false">
										<label for="dairyfree" style="vertical-align: text-bottom;">Dairy-Free</label><br/>
									</td>
								</tr>
										
								<tr>
									<td style="cursor: default; padding-top: 5px;">
										<input type="submit" value="Search" class="seventh">
										<?php
											if($log == "Logout"){
												echo '<input type="submit" name="myrecipes" value="View My Recipes" class = "seventh"/>';
											}
										?>
										<input type="reset" value="Clear" class="seventh">
									</td>
								</tr>
							</table>
						</td>
						
						<!-- Begin Right (results) Column -->
						<td style="width: 70%; vertical-align: top; border-left: 1px solid #888; padding-left: 5px;">
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
						</td>
					</tr>
				</table>
            </form>
        </div>
    </body>
</html>
