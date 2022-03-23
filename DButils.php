<?php

	//returns a connection to the local main database over a unix socket
	function getDefaultDB() {
		return pg_connect('host=localhost user=fetcher1 password=1234 dbname=main');
	}
	
	//returns true if the passwords should be considered to be equal, false otherwise
	function comparePasswords($givenPassword, $DBPassword, $userid) {
		if($givenPassword == $DBPassword){
			return True;
		}
		else{
			return False;
		}
	}
	
	//converts the given "password" string to one suitable for storing in the database
	//(will hash/salt it)
	function passwordToDB($password, $userid) {
		return $passwd;
	}
	
	/* function doesUserExist($db, $email) : string|false|null{
		$res = pg_query_params($db, "SELECT userid FROM customers WHERE email=$1;", $email);
	} */
	
	//gets user info from the given database using the given identifier
	//$db = the database connection to get the info from (use getDefaultDB?)
	//$identifier = whatever information you're using to identify the user. Valid entries are:
	//	"email"
	//	"userid"
	//	anything else is the same as "userid"
	/* function getUserInfo($db, $identifier, $byWhat = "userid") : Array|string {
		if($byWhat == "email"){
			$res = pg_query_params($db, "SELECT * FROM customers WHERE email=$1;", $identifier);
		}else{
			$res = pg_query_params($db, "SELECT * FROM customers WHERE userid=$1;", $identifier);
		}
		
		
		if($res == false){
			return pg_last_error($db);
		}else{
			if(pg_num_rows($res) == 0){
				return Array();
			}
			return pg_fetch_assoc($res);
		}
	} */
	
	//checks if $givenPassword is the same as the password for the user with $userid
	//uses $db as the database to connect to
	/* function validatePassword($db, $userid, $givenPassword) : boolean|string|null{
		$res = pg_query_params($db, "SELECT password FROM customers WHERE userid=$1;", Array($userid));
		if($res == false){
			return null;
		}else if(pg_num_rows($res) != 1){
			return null;
		}else{
			return pg_fetch_row($res, 0)[0] === $givenPassword;
		}
	} */
	
	/* function makeUser($db, ) {
		
	} */
?>
