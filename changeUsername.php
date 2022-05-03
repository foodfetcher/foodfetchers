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
				
				$newUsername = $_POST['newUsername'];
				
				if (isset($newUsername)){
					pg_query_params($db, "UPDATE customers SET username=$1 WHERE email=$2;", array($newUsername, $email));
				}

				$toOutput = "Successfully changed username</br>";
				$_POST = array();

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
			<form name = "changeUsername" action = "changeUsername.php" method = "post" autocomplete="off">
                <h1>Change Username </h1>

				<label for="newUsername">New Username</label>
                <input type = "text" name = "newUsername" id = "newUsername" required>

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
