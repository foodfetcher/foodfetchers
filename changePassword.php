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
		<script>
			history.pushState(null, null, document.URL);  //prevents going back to previous form state
			window.addEventListener('popstate', function () {
				history.pushState(null, null, document.URL);
				window.history.replaceState(null, null, document.URL);
			});
		</script>
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
				$row = pg_fetch_assoc($res);

				if (!comparePasswords($existingPass, $row["passwd"], $userid)){  //password must match one already in database
					$toOutput = "Error: Existing password does not match.";
					$existingPassError = 1;
				}
				else if ($newPass != $confirmPass){  //confirm pass must match new pass
					$toOutput = "Error: New password and Confirm password do not match.";
					$confirmPassError = 1;
				}
				else if (comparePasswords($newPass, $row["passwd"], $userid)){  //new password must not be the existing password
					$toOutput = "Error: New password must be different than existing password.";
					$newPassError = 1;
				}
				else {
					$passForDB = passwordToDB($newPass, $userid);
					
					//echo $passForDB . "</br>";
					//echo $email . "</br>";
					
					pg_query_params($db, "UPDATE customers SET passwd=$1 WHERE email=$2;", array($passForDB, $email));
					
					/*$query = "SELECT * FROM customers WHERE email='$email'";
					$res = pg_query($db, $query);
					$row = pg_fetch_assoc($res);
					echo $row["passwd"];*/
					
					$toOutput = "Successfully changed password</br>";
					$_POST = array();
					//session_destroy();
					//echo '<meta http-equiv="refresh" content="3;url=viewAccount.php" />';
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
			<form name = "changePassword" action = "changePassword.php" method = "post" autocomplete="off">
                <h1>Change Password </h1>
                
				<label for="existing">Existing Password:*</label>
                <input type = "password" name = "existing" id = "existing" value="<?php if(isset($_POST['existing'])){ echo htmlentities($_POST['existing']);}?>"
																					<?php if($existingPassError == 1){ echo 'style="border:1px solid red;"';}?> required></br>
				<label for="newpass">New Password:*</label>
                <input type = "password" name = "newpass" id = "newpass" value="<?php if(isset($_POST['newpass'])){ echo htmlentities($_POST['newpass']);} ?>"
																					<?php if($newPassError == 1 || $confirmPassError == 1){ echo 'style="border:1px solid red;"';}?> required></br>
				<label for="confirmpass">Confirm Password:*</label>
                <input type = "password" name = "confirmpass" id = "confirmpass" value="<?php if(isset($_POST['confirmpass'])){ echo htmlentities($_POST['confirmpass']);}?>"
																							<?php if($confirmPassError == 1){ echo 'style="border:1px solid red;"';}?> required></br>
                
                <input type="submit" value = "Submit">
			</form>
			</br>
			<?php
				echo $toOutput;
			?>
        </div>
		</div>
    </body>
</html>
