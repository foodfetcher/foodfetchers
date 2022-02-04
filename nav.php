<?php
    echo '<div id = "header">
		<div id = "logo">
			<a href = home.php>
				<img src="Images/logo.png" width="100" height="90" alt="Food Fetcher Logo">
			</a>
		</div>
		
		<div id = "nav">
        <a href = home.php>Home</a>';
		
        if($log == "Login"){
            echo "<a href = signup.php>Sign Up</a>";
        }
		
        echo '<a href = browse.php>Browse Recipes</a>
        <a href = info.php>Info</a>';
		
        if($log == "Logout"){
            $first = $_SESSION["firstname"];
            echo "<a href = create.php>Create Recipe</a>
				<a href = viewMeals.php>My Meals</a>";
            echo "<div id = 'logout'>Hello, $first<br><a href = login.php>$log</a></div>";
        }
        else{
            echo "<a href = login.php>$log</a>";
        }
    echo'</div></div>'; 
?>