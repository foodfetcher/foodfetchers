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
				$email = strtolower($_POST['email']);
				$password = $_POST['password'];
				
				$db = getDefaultDB();
				
				$query = "SELECT * FROM customers WHERE email='$email'";
				$res = pg_query($db, $query);
				
				//echo pg_last_error($db);
				if(pg_num_rows($res) == 0){
					$outcome = "Incorrect email or password (email)";
				}
				else{
					$row = pg_fetch_assoc($res);
					//print_r($row);
					if($password != $row["passwd"]){
						$outcome = "Incorrect email or password (password)";
					}
					else{
						$outcome = "success";
						echo '<meta http-equiv="refresh" content="3;url=home.php" />';
						$log = "Logout";
						$_SESSION["userid"] = $row["userid"];
						$_SESSION["firstname"] = $row["firstname"];
						$_SESSION["lastname"] = $row["lastname"];
						$_SESSION["email"] = $row["email"];
						$_POST = array();
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
        <div id = "Content" style = "max-width: 320px;">
            <h1> Log In </h1>
			<form name = "login" action = "login.php" method = "post" onSubmit="">
				<table style="width: 100%;"><tr><td style="display: flex;">
					<label for="email" style="flex: 0; white-space: pre; padding-top: 4px;">E-Mail Address: </label>
					<input type = "email" name = "email" id = "email" style="flex: 1;" value="<?php if(isset($_POST['email'])){ echo htmlentities($_POST['email']);}?>" required></td></tr>
					<tr><td style="display: flex;"><label for="password" style="flex: 0; white-space: pre; padding-top: 4px;">Password: </label>
					<input type = "password" name = "password" id = "password" style="flex: 1;" value="<?php if(isset($_POST['password'])){ echo htmlentities($_POST['password']);}?>" required></td></tr>
					
					<tr><td><input type="submit" value = "Log In">
					<!--<input type="reset" value = "Clear">-->
				</td></tr></table>
			</form>
            <?php 
                if($outcome == 'success'){
                    echo '<p>Welcome back ' . $_SESSION["firstname"] . '!<br>Redirecting you to <a href="home.php">Home</a>.</p>';
				}
				else{
					if($db === false){
						echo "Error: Could not connect to user database.";
					}
					else{
						echo $outcome;
					}
				}
			?>
		</div>
	</body>
</html>
