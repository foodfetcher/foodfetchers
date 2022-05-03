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
        <title> Food Fetchers | Your Account </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <style>
			.favorites table, .mealPlans table, .meals table{
				width: 100%;
			}

			.favorites td, .meals td {
				width: 10%;
				vertical-align: top;
			}

			.mealPlans tr {
				height: 25px;
			}

			.mealPlans td {
				width: 8%;
			}
		</style>
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
			include "DButils.php";
			$db = getDefaultDB();
			$maxCol = 6;
        ?>
        <div id="Content">
            <h1> My Profile</h1>
			<div id="profile">
				<?php
					$email = $_SESSION["email"];
					$res = pg_query_params($db, "SELECT username FROM customers WHERE email=$1", array($email));
					while($row = pg_fetch_assoc($res)){
						$username = $row["username"];
					}
					//$username = $_SESSION["username"];
					$userid = $_SESSION['userid'];
					echo "<div id='Name'>Name: $username</div>";
					echo "<div id='Email'>Email: $email</div>";
					echo '</br><a id="username" href=changeUsername.php>Change Username</a></br>';
					echo '</br><a id="password" href=changePassword.php>Change Password</a></br></br>';
				?>
			</div>
        </div>
		<div class="profile">
			<h2> Favorites </h2>
			<?php
				$DB_HOST='localhost';
				$DB_USER='fetcher1';
				$DB_PASS='1234';
				$DB_NAME='main';
				$db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");

				//display account recipes
				$favoriteRes = pg_query_params($db, "SELECT * FROM favorites WHERE userid=$1", Array($userid));

				if ($favoriteRes === false){
					echo 'It looks like you do not have any favorited recipes. Go to the browse page to discover some new favorites!';
				}
				else{
					echo '<table class="favorites">';
					$count = 0;
					echo '<tr>';
					while($row = pg_fetch_assoc($favoriteRes)){
						$recipeid = $row["recipeid"];
						$filename = '/var/www/html/foodFetchers/master/coverimages/' . $recipeid;

						if ($count == $maxCol){
							$count = 0;
							echo '</tr><tr>';
						}

						echo '<td>';
						if (file_exists($filename)) {
							echo '<a href="view.php?id=' . $recipeid . '"><img id="favImage" src="coverimages/' . $recipeid . '" alt="recipe cover image" style="width:100px; height:100px; object-fit:cover;"></a></br>';
						}
						else {
							echo '<a href="view.php?id=' . $recipeid . '"><img id="favImage" src="coverimages/logo.png" alt="recipe cover image" style="width:100px; height:100px; object-fit:cover;"></a></br>';
						}

						$recipeName = pg_query_params($db, "SELECT recipename FROM recipes WHERE recipeid=$1", Array($recipeid));
						$row = pg_fetch_assoc($recipeName);
						echo '<a href="view.php?id=' . $recipeid . '">' . $row['recipename'] . '</a></br>';
						echo '</td>';
						$count++;
					}
					echo '</tr>';
					echo '</table>';
				}
				//pg_close($db);
			?>
		</div>
		<div class="profile">
			<h2> Meal Plans </h2>
			<?php
				/*$DB_HOST='localhost';
				$DB_USER='fetcher1';
				$DB_PASS='1234';
				$DB_NAME='main';
				$db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
				*/
				//display account recipes
				$res = pg_query_params($db, "SELECT * FROM meals WHERE customerid=$1", Array($userid));

				if ($res === false){
					echo 'It looks like you do not have any meal plans. Go to the meal planner to create some meal plans!';
				}
				else{
					$count = 0;

					echo '<table class="mealPlans">';
					echo '<tr>';

					while($row = pg_fetch_assoc($res)){
						if ($count == $maxCol){
							$count = 0;
							echo '</tr><tr>';
						}
						//echo '<td>';
						echo '<td>';
						echo '<a href="viewMeal.php?id=' . $row["mealid"] . '"><div>' . $row['mealname'] . '</div></a>';
						echo '</td>';
						$count++;
					}

					echo '</tr>';
					echo '</table>';
				}
				//pg_close($db);
			?>
		</div>
		<div class="profile">
			<h2> Your Recipes </h2>
			<?php
				/*$DB_HOST='localhost';
				$DB_USER='fetcher1';
				$DB_PASS='1234';
				$DB_NAME='main';
				$db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
				*/
				//display account recipes
				$res1 = pg_query_params($db, "SELECT * FROM recipes WHERE creatorid=$1", Array($userid));

				if ($res1 === false){
					echo 'It looks like you do have not created any recipes. Go to the create recipe page to make one!';
				}
				else{
					$count = 0;

					echo '<table class="meals">';
					echo '<tr>';

					while($row = pg_fetch_assoc($res1)){
						$recipeid = $row["recipeid"];
						$filename = '/var/www/html/foodFetchers/master/coverimages/' . $recipeid;

						if ($count == $maxCol){
							$count = 0;
							echo '</tr><tr>';
						}

						echo '<td>';
						if (file_exists($filename)) {
							echo '<a href="view.php?id=' . $recipeid . '"><img id="recipeImage" src="coverimages/' . $recipeid . '" alt="recipe cover image" style="width:100px; height:100px; object-fit:cover;"></a></br>';
						}
						else {
							echo '<a href="view.php?id=' . $recipeid . '"><img id="recipeImage" src="coverimages/logo.png" alt="recipe cover image" style="width:100px; height:100px; object-fit:cover;"></a></br>';
						}

						$recipeName = pg_query_params($db, "SELECT recipename FROM recipes WHERE recipeid=$1", Array($recipeid));
						$row = pg_fetch_assoc($recipeName);
						echo '<a href="view.php?id=' . $recipeid . '">' . $row['recipename'] . '</a></br>';
						echo '</td>';
						$count++;
					}

					echo '</tr>';
					echo '</table>';
				}
				pg_close($db);
			?>
		</div>
    </body>
</html>
