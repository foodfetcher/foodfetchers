<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Log In </title>
        <link rel="stylesheet" href="phaseIstyle.css">
		<?php
			session_start();
			$log = 'Login';
			if (isset($_SESSION['userid'])) //since this page becomes the logout page we can reset the session when entering this page with the session variable set
			{
				$log = "Logout";
				$_SESSION = array();
				if (ini_get("session.use_cookies")) {
					$params = session_get_cookie_params();
					setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
					);
				}
				session_destroy();
				$targetLocation = $_SERVER['HTTP_REFERER'] ?? "home.php";
				header("Location: $targetLocation");
				die('<a href="home.php">Click here if you are not automatically redirected</a>');
			}
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				include "DButils.php";
				$email = strtolower($_POST ['email']);
				$password = $_POST ['password'];
				
				$db = getDefaultDB();
				
				$query = "SELECT * FROM customers WHERE email='$email'";
				$res = pg_query($db, $query);
				//echo $res;
				//echo pg_last_error($db);
				if(pg_num_rows($res) != 1){
					$outcome = "Email not recognised";
				}
				else{
					$row = pg_fetch_assoc($res);
					//print_r($row);
					if($password == $row["password"]){
						$outcome = "Password does not match.";
						}else{
						$outcome = "success";
						echo '<meta http-equiv="refresh" content="5;url=home.php" />';
						$log = "Logout";
						$_SESSION["userid"] = $row["userid"];
						$_SESSION["firstname"] = $row["firstname"];
						$_SESSION["lastname"] = $row["lastname"];
					}
				}
				pg_close($db);
			} 
		?>
		
	</head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
		?> 
        <div id = "Content">
            <form name = "login" action = "login.php" method = "post" onSubmit="">
                <h1> Log in </h1>
                <label for="email">E-Mail Address</label>
                <input type = "email" name = "email" id = "email" required>
                <label for="password">Password</label>
                <input type = "password" name = "password" id = "password" required> </br>
                
                <input type="submit" value = "Submit">
                <input type="reset" value = "Clear">
                
			</form>
            <?php 
                if($outcome == 'success'){
                    echo '<p>Successfully logged in. Redirecting to <a href="home.php">home page</a> in 5 seconds... (click the link if this doesn\'t happen automatically)</p>';
				}
				else{
					if($db === false){
						echo "error: Could not connect to user database.";
					}
					else{
						echo $outcome;
					}
				}
			?>
		</div>
	</body>
</html>
