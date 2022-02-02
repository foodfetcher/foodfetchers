<html>
    <head>
        <title> Food Fetchers | sign up </title>
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
				
				//TODO: check if account already exists before creating it, otherwise accounts get overwritten
				$res = pg_query_params($db, "INSERT INTO Customers (Email, Passwd, FirstName, LastName, Address1, Address2, ZipCode, State, PhoneNumber, City) VALUES ('$1', '$2', '$3', '$4', '$5', '$6', '$7', '$8', '$9', '$10')",
				strtolower($_POST['email']),
				$_POST['password'],
				$_POST['firstName'],
				$_POST['lastName'],
				$_POST['address'],
				$_POST['address2'],
				$_POST['zip'],
				$_POST['state'],
				$_POST['number'],
				$_POST['city']);
				if ($res){
					$outcome = "success";
					echo '<meta http-equiv="refresh" content="5;url=login.php" />';
				}
				else{
					$outcome = "unable to create acount";
				}
				pg_close($db);
			}
			$log = "Login";
			
		?>
	</head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
		?> 
        <div id = "Content">
            <form name = "signup" action = "signup.php" method = "post">
                <h1> Create an Account </h1>
                <label for="firstName" class = "names">First Name:*</label>
                <input type = "text" name = "firstName" id = "firstName" required>
                <label for="lastName" class = "names">Last Name:*</label>
                <input type = "text" name = "lastName" id = "lastName" required> </br>
                <label for="email" class = "second">Email Address:*</label>
                <input type = "email" name = "email" id = "email" required>
                <label for="password" class = "second">Password:*</label>
                <input type = "password" name = "password" id = "password" required> </br>
                <label for="address" class = "third">Address:*</label>
                <input type = "text" name = "address" id = "address" required>
                <label for="city" class = "third">City:*</label>
                <input type = "text" name = "city" id = "city" required> </br>
                <label for="state" class = "fourth">State:*</label>
                <input type = "text" name = "state" id = "state" required>
                <label for="zip" class = "fourth">Zip Code:*</label>
                <input type = "number" name = "zip" id = "zip" required> </br>
                <label for="address2" class = "fifth">Address 2:</label>
                <input type = "text" name = "address2" id = "address2">
                <label for="number" class = "fifth">Phone Number:</label>
                <input type = "number" name = "number" id = "number"> </br>
                <label class = "sixth">* Denotes required field </label> </br>
                
                <input type="submit" value = "Submit" class = "seventh">
                <input type="reset" value = "Clear" class = "seventh">
			</form>
            
            <?php 
                if($outcome == 'success'){
                    echo '<p>Account successfully created. Redirecting to <a href="login.php">login page</a> in 5 seconds... (click the link if this doesn\'t happen automatically)</p>';
				}
				else{
					echo $outcome;
				}
			?>
		</div>
	</body>
</html>    