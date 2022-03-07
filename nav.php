<?php
    echo '<div id = "header">
		<a href = home.php>
			<div id = "logo">
				<img src="Images/logo-dark.png" width="75" height="90" alt="Food Fetcher Logo">
			</div>
		</a>
		
		<div id = "nav">
        <a href = home.php>Home</a>';
		
        echo '<a href = browse.php>Browse Recipes</a>';
		
        if($log == "Logout"){
            $first = $_SESSION["firstname"];
            echo "<a href = create.php>Create Recipe</a>
				<a href = viewMeals.php>Meal Plans</a>";
            echo "<div id = 'logout'>Hello, $first<br><a href = login.php>$log</a></div>";
        }
        else{
            echo "<a href = login.php>$log</a>";
        }
		
		if($log == "Login"){
            echo "<a id='signup' href = signup.php>Sign Up</a>";
        }
		
    echo'</div></div><div id = "headpad"></div>'; 
?>