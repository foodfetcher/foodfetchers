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
        <?php
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				include "DButils.php";
				$db = getDefaultDB();
				
				$email = $_SESSION["email"];
				$userid = $_SESSION["userid"];
				
				$existingPass = $_POST['existing'];
				$newPass = $_POST['newpass'];
				$confirmPass = $_POST['confirmpass'];
				
				$query = "SELECT * FROM customers WHERE email='$email'";
				$res = pg_query($db, $query);
				//echo $res . "</br>";
				//echo $email . "</br>";
				$row = pg_fetch_assoc($res);
				
				//echo $row["passwd"] . "</br>";
				
				if (!comparePasswords($existingPass, $row["passwd"], $userid)){
					$toOutput = "Error: Existing password does not match.";
				}
				else if ($newPass != $confirmPass){
					$toOutput = "Error: New password and Confirm password do not match.";
				}
				else if (comparePasswords($newPass, $row["passwd"], $userid)){
					$toOutput = "Error: New password must be different than existing password.";
				}
				else {
					$passForDB = passwordToDB($newPass, $userid);
					$toOutput = "Successfully changed password. Redirecting back to profile page...</br>Click <a href=viewAccount.php>here</a> if not automatically redirected.";
					
					
					
					echo '<meta http-equiv="refresh" content="3;url=viewAccount.php" />';
					$log = "Logout";
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
			<form name = "changePassword" action = "changePassword.php" method = "post">
                <h1>Change Password </h1>
                <label for="existing">Existing Password:*</label>
                <input type = "password" name = "existing" id = "existing" required <?php echo $_POST['existing'];?>></br>
				<label for="newpass">New Password:*</label>
                <input type = "password" name = "newpass" id = "newpass" required></br>
				<label for="confirmpass">Confirm Password:*</label>
                <input type = "password" name = "confirmpass" id = "confirmpass" required></br>
                
                <input type="submit" value = "Submit" class = "seventh">
                <input type="reset" value = "Clear" class = "seventh">
			</form>
			</br>
			<?php
				echo $toOutput;
			?>
        </div>
		</div>
    </body>
</html>
