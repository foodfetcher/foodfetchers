<?php

	//returns a connection to the local main database over a unix socket
	function getDefaultDB() {
		return pg_connect('host=localhost user=fetcher1 password=1234 dbname=main');
	}

	//returns true if the passwords should be considered to be equal, false otherwise
	function comparePasswords($givenPassword, $DBPassword) {
		return password_verify($givenPassword, $DBPassword);
	}

	//converts the given "password" string to one suitable for storing in the database
	//(will hash/salt it)
	function passwordToDB($password) {
		return password_hash($password, PASSWORD_DEFAULT);
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
    function showIngredients($ingredients, $db){
            $groceryList = Array();
                $ingrArr = json_decode($ingredients);
                foreach($ingrArr as $ingrRow){
                    $match = false;
                    $ingrRow->ingr=ltrim($ingrRow->ingr," ");
                    $ingrRow->ingr=rtrim($ingrRow->ingr," ");
                    foreach($groceryList as $exists => $total){
                        if(strtolower($exists) == strtolower($ingrRow->ingr) && $total[1] == $ingrRow->unit){
                            $groceryList[$exists] = array($ingrRow->num + $total[0],$ingrRow->unit);
                            $match = true;
                        }
                    }
                    if(!$match){
                            $groceryList = $groceryList + array($ingrRow->ingr =>array($ingrRow->num,$ingrRow->unit));
                        }
                }
            
            return $groceryList;
            
        
    }
    function getIngredients($planId, $db){
        $res = pg_query($db, "SELECT ingredients FROM mealline INNER JOIN
        recipes ON mealline.recipeid=recipes.recipeid WHERE mealid=$planId
        ");
        echo pg_last_error($db);
        
            $groceryList = Array();
            while($row = pg_fetch_assoc($res)){
                $ingrArr = json_decode($row["ingredients"]);
                foreach($ingrArr as $ingrRow){
                    $match = false;
                    $ingrRow->ingr=ltrim($ingrRow->ingr," ");
                    $ingrRow->ingr=rtrim($ingrRow->ingr," ");
                    foreach($groceryList as $exists => $total){
                        if(strtolower($exists) == strtolower($ingrRow->ingr) && $total[1] == $ingrRow->unit){
                            $groceryList[$exists] = array($ingrRow->num + $total[0],$ingrRow->unit);
                            $match = true;//$cy=$cy+1;echo "Cycle: ".$cy. "Ex: ".$exists. "<br>";
                        }
                    }
                    if(!$match){
                            $groceryList = $groceryList + array($ingrRow->ingr =>array($ingrRow->num,$ingrRow->unit));
                            //echo 'gl: ';
                            //print_r($groceryList);echo '<br>';
                        }
                    //echo '<li>'.$ingrRow->num.' '.$ingrRow->unit.' '.$ingrRow->ingr.'</li>';
                }
            
            }
            //print_r($groceryList);
            return $groceryList;
            
        
    }
    function listIngredients($planId, $db){
        $groceryList= getIngredients($planId, $db);
        echo '<ul class="ingredients-list">';
        foreach($groceryList as $ingredient => $quantity){
                echo '<li>' . $quantity[0] . ' '. $quantity[1] . ' ' . $ingredient . '</li>';
            }
        echo '</ul>';
    }
?>
