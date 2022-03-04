<div id = "left-nav-container">
	<div id = "left-nav">
		<div id= "left-nav-tile" style="padding: 2vh .5vw 2vh .5vw;">
			<?php
				$first = $_SESSION["firstname"];
				echo "<a>Hello, $first</a>";
			?>
		</div>
		<div id= "left-nav-tile" style="border-top: 2px solid white">
			<a href=browse.php >Browse Recipes</a>
		</div>
		<div id= "left-nav-tile" style="border-bottom: .7px solid white; border-top: .7px solid white">
			<a href=viewMeals.php >New Meal Plan</a>
		</div>
		<div id= "left-nav-tile" style="border-bottom: 2px solid white; pointer-events:none;">
			<a>My Meal Plans</a>
		</div>
		<?php
			$DB_HOST='localhost';
			$DB_USER='fetcher1';
			$DB_PASS='1234';
			$DB_NAME='main'; 
			$db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
			
			$userid = $_SESSION['userid'];
			$res = pg_query($db, "SELECT * FROM meals WHERE customerid='$userid'");
			while($row = pg_fetch_assoc($res)){
				echo '<div id = "left-nav-tile"><a href="viewMeal.php?id=' . $row["mealid"] . '">' . $row['mealname'] . '</a></div>';
			}
			pg_close($db);
		?>
		<div id= "left-nav-tile" style="border-bottom: 2px solid white; border-top: 2px solid white">
			<a>Grocery List</a>
		</div>
	</div>
</div>