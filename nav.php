<?php
    echo '<nav id="navbar"><div id = "header">
		<a href = home.php>
			<div id = "logo">
				<img src="Images/logo-dark.png" width="65" height="85" alt="Food Fetcher Logo">
			</div>
		</a>
		
		<div id = "nav">
        <a href = home.php>Home</a>';
		
        echo '<a href = browse.php>Browse Recipes</a>';
		
        if($log == "Logout"){
            $username = $_SESSION["username"];
            echo "<a href = create.php>Create Recipe</a>
				<a href = MealPlans.php>Meal Plans</a>";
            echo "<div id = 'logout'>Hello, <a href=viewAccount.php>$username</a><br><a href = login.php>$log</a></div>";
        }
        else{
            echo "<a href = login.php>$log</a>";
        }
		
		if($log == "Login"){
            echo "<a id='signup' href = signup.php>Sign Up</a>";
        }
		
    echo'</div></div><div id = "headpad"></div></nav>'; 
?>