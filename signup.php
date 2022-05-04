<html>
    <head>
        <title> Food Fetchers | Sign Up </title>
        <link rel="stylesheet" href="phaseIstyle.css">
		<?php
			session_start();
			if (isset($_SESSION['userid']))
			{
				$log = "Logout";
				header("Location: home.php");
				die('<a href="home.php">Click here if you are not automatically redirected</a>');
			}
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				include "DButils.php";
				$db = getDefaultDB();

				$res = pg_query_params($db, "SELECT email FROM Customers WHERE email=$1", array(strtolower($_POST['email'])));
				if(pg_num_rows($res) != 0){
					$outcome = 'Account with that email already exits! <a href="login.php">Login?</a>';
				}
				else{
					$res2 = pg_query_params($db, "INSERT INTO customers (email, username, passwd) VALUES ($1, $2, $3) RETURNING userid", array(
					strtolower($_POST['email']),
					$_POST['username'],
					"placeholder"));
					if (pg_num_rows($res2) == 1){
						$outcome = "success";
						$userid = pg_fetch_assoc($res2)["userid"];
						pg_query_params($db, "UPDATE customers SET passwd=$1 WHERE userid=$2;", Array(passwordToDB($_POST['password']),$userid));
						echo '<meta http-equiv="refresh" content="3;url=login.php" />';
					}
					else{
						$outcome = "response: " . var_export($res2, true) . "<br/>err: " . pg_last_error($db) . "<br/>ResponseLen: " . pg_num_rows($res2) . "<br/>res:" . print_r(pg_fetch_assoc($res2));
					}
				}

				pg_close($db);
			}
			$log = "Login";

		?>
	</head>
    <body>
		<div id="background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
		?>
        <div id = "Content" style = "width: 400px;">
            <form name = "signup" action = "signup.php" method = "post">
                <h1> Create an Account </h1>
                <label for="email">Email Address:*</label>
                <input type = "email" name = "email" id = "email" required> </br>
                <label for="username">Username:*</label>
                <input type = "text" name = "username" id = "username" required> </br>
                <label for="password">Password:*</label>
                <input type = "password" name = "password" id = "password" required> </br>
                <label>* Denotes required field </label> </br>

                <input type="submit" value = "Submit">
                <input type="reset" value = "Clear">
			</form>

            <?php
                if($outcome == 'success'){
                    echo '<p>Account successfully created. Redirecting to <a href="login.php">login page</a> in 5 seconds... (click the link if this doesn\'t happen automatically)</p>';
				}
				else{
					if(isset($outcome)){
						echo "Unable to create account:<br/>" . $outcome;
					}

				}
			?>
		</div>
	</body>
</html>
