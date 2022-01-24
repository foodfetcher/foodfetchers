<?php
	session_start();
	if (isset($_SESSION['userid']))
	{
		$log = "Logout";
        header("Location: home.php");
        die('<a href="home.php">Click here if you are not automatically redirected</a>');
    }
	elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
		$log = "Login";
        
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = strtolower($_POST['email']);
        $password = $_POST['password'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $address2 = $_POST['address2'];
        $number = $_POST['number'];
        
        
        $DB_HOST='localhost';
        $DB_USER='fetcher1';
        $DB_PASS='1234';
        $DB_NAME='main'; 
        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
		//TODO: check if account already exists before creating it, otherwise accounts get overwritten
        $res = pg_query($db, "INSERT INTO Customers (Email, Passwd, FirstName, LastName, Address1, Address2, ZipCode, State, PhoneNumber, City) VALUES ('$email', '$password', '$firstName', '$lastName', '$address', '$address2', '$zip', '$state', '$number', '$city')");
        if ($res)
        $outcome = "success";
        else
        $outcome = "unable to create acount";
        pg_close($db);
    }
	$log = "Login";
	
?>

<html>
    <head>
        <title> Food Fetchers | sign up </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <?php
            if($outcome == 'success'){
                echo '<meta http-equiv="refresh" content="5;url=login.php" />';
            }
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
            ?>
        </div>
    </body>
</html>    