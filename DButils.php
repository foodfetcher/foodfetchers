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
    
    function toDecimal($val){
        if(isset($val)){
            if(strstr($val,"/")){
                $space = strpos($val, " ");
                $whole = substr($val,0, $space);
                $slash = strpos($val,"/");
                $numerator = substr($val,$space+1, $slash-$space-1);
                $denominator = substr($val, $slash+1);
                $val = $whole + $numerator / $denominator;
                
            }
        }
        return $val;
    }
    function toFraction($gl){
        $fraction = array();
        $fraction['1/8'] = 0.125;
        $fraction['1/3'] = 0.333;
        $fraction['1/4'] = 0.25;
        $fraction['3/8'] = 0.375;
        $fraction['1/2'] = 0.5;
        $fraction['5/8'] = 0.625;
        $fraction['2/3'] = 0.667;
        $fraction['3/4'] = 0.75;
        $fraction['7/8'] = 0.875;
        
        foreach($gl as $ingredient => $quantity){
            if($quantity[2] == "true"){
            $gl[$ingredient][0] = round($quantity[0],3);
            $wholeComp = floor($quantity[0]);
            $fracComp = round($quantity[0],3) - $wholeComp;
            
            foreach($fraction as $arr=>$comp){
                if(round($fracComp,3) == round($comp,3)){
                    if($wholeComp){
                        $gl[$ingredient][0] = $wholeComp.' '.$arr;
                    } else {
                        $gl[$ingredient][0] = $arr;
                    }
                    break;
                }
            }
            }
        }
        return $gl;
        
    }
    function showIngredients($ingredients, $db){
            $groceryList = Array();
                $ingrArr = json_decode($ingredients);
                foreach($ingrArr as $ingrRow){
                    $match = false;
                    $ingrRow->ingr=ltrim($ingrRow->ingr," ");
                    $ingrRow->ingr=rtrim($ingrRow->ingr," ");
                    foreach($groceryList as $exists => $total){
                        if(strtolower($exists) == strtolower($ingrRow->ingr) && $total[1] == $ingrRow->unit){
                            $groceryList[$exists] = array(toDecimal($ingrRow->num) + $total[0],$ingrRow->unit, $ingrRow->frac);
                            $match = true;
                        }
                    }
                    if(!$match){
                            $groceryList = $groceryList + array($ingrRow->ingr =>array(toDecimal($ingrRow->num),$ingrRow->unit, $ingrRow->frac));
                        }
                }
            $groceryList = toFraction($groceryList);
            return $groceryList;
            
        
    }
    function getIngredients($planId, $db){
        $res = pg_query_params($db, "SELECT ingredients FROM mealline INNER JOIN
        recipes ON mealline.recipeid=recipes.recipeid WHERE mealid=$1
        ", Array($planId));
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
                            $groceryList[$exists] = array(toDecimal($ingrRow->num) + $total[0],$ingrRow->unit, $ingrRow->frac);
                            $match = true;
                        }
                    }
                    if(!$match){
                            $groceryList = $groceryList + array($ingrRow->ingr =>array(toDecimal($ingrRow->num),$ingrRow->unit, $ingrRow->frac));
                        }
                }
            
            }
            $groceryList = toFraction($groceryList);
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
