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
        $loginError = '<p>Logged Out... Redirecting Home...</p>';
    }
    
	include 'DButils.php';
	
    $mealid = (int)$_GET["id"];
    $mealInfo = array();
    $db = getDefaultDB();
 
    $DB_HOST='localhost';
    $DB_USER='fetcher1';
    $DB_PASS='1234';
    $DB_NAME='main'; 
    $userid = $_SESSION['userid'];
    $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
    
   //handle recipe deletion
	if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["delete"]) && filter_var($_POST["delete"], FILTER_VALIDATE_BOOLEAN)){
        $_SESSION["lastDeleted"]=$_POST["saveName"];
		$res = pg_query_params($db, "SELECT mealid FROM meals WHERE mealid=$1 AND customerid=$2;", Array($_POST["mealid"], $userid));
		if(pg_num_rows($res) == 0){
			$deleteOutcome = "You do not have permission to edit this meal plan!";
		}else{
			$res = pg_query_params($db, "DELETE FROM meals WHERE mealid=$1;", Array($_POST["mealid"]));
			if($res == false){
				$deleteOutcome = pg_last_error($db);
			}else{
				$deleteOutcome = "Success!";
			}
		}
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Grocery List </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <link rel="stylesheet" href="styles-MealPlans.css">
        <script>
        function scrollToTop(el){
            el.scrollIntoView({behavior:"smooth"});
            toggleOpen(el);
        }
        function toggleOpen(el){
            var viewMeal = el.parentElement.nextElementSibling;
            var viewIngredients = viewMeal.nextElementSibling;
            
            if (viewMeal.style.height) {
                el.classList.toggle("open");
                viewMeal.style.height = null;
                viewIngredients.style.height = null;
            } else {
                el.classList.toggle("open");
                viewMeal.style.height = "calc(90% - 1vh)";
                viewIngredients.style.height = "calc(90% - 1vh)";
            } 
        }
        function openIngredients(el){
            var viewMeal = el.parentElement.parentElement;
            var viewIngredients = viewMeal.nextElementSibling;
            viewMeal.style.width = "0";
            viewIngredients.style.width = "100%";
        }
        function closeIngredients(el){
            var viewIngredients = el.parentElement.parentElement;
            var viewMeal = viewIngredients.previousElementSibling;
            viewMeal.style.width = "100%";
            viewIngredients.style.width = null;
        }
        function printWindow(el){
            var planId = el.getAttribute("data-planname");
            document.getElementById("print-subtitle").innerHTML = planId;
            var list = document.getElementById("print-info");
            list.innerHTML= el.nextElementSibling.innerHTML;
            list.firstElementChild.classList.toggle("print-list");
            window.print();
        }
        function showScroll(){
            var elt = document.getElementById('plan-display');
            if(elt.clientHeight < (elt.scrollHeight - document.getElementById('bottom-spacer').scrollHeight - 8)){
                document.getElementById('scroll-image').style.display = "unset";
            }
        }
        </script>
    </head>
    <body onload="showScroll()">
        <div id = "background">
        <?php
            include 'nav.php'; //write out the nav bar
            include 'deletePlan.php';//delete confirmation modal
        ?> 
            <div id = "plan-display">
                <?php
                    $res = pg_query_params($db, "SELECT * FROM meals WHERE customerid=$1", Array($userid));
                    echo $loginError;
                    while($row = pg_fetch_assoc($res)){
                    $planName=$row["mealname"];
                    echo '<div class="plan-title">
                              <div class="plan-title-text" onclick="scrollToTop(this)" >'.$planName.'</div>
                          </div>';
                                              
                    $mealid = $row['mealid'];
                    include 'viewMeal.php';
                    include 'viewIngredients.php';
                    }
                    echo '<div id="bottom-spacer"></div>';
                    pg_close($db);
                ?>
            </div>
            <a id="new-plan" href=newPlan.php>
                <div id="plus">&#x2B;</div>
                <p>New Meal Plan</p>
            </a>
            <img id="scroll-image" src="Images/scrollDown.gif">
        </div>
        <div class="print-header">
            <div id="print-title">Grocery List</div>
            <div id="print-subtitle">Grocery List</div>
        </div>
        <div class="print-footer">
            <img src="Images/logo-Alt.png" width="10%" alt="FOOD FETCHER">
        </div>
        <table class="print-page">
            <thead style="border:2px solid red;"><tr><td>
                <div class="header-pad">&nbsp;</div>
            </td></tr></thead>
            <tbody><tr><td>
                <div id="print-info"> hello</div>
            </td></tr></tbody>
            <tfoot><tr><td>
                <div class="footer-pad" >&nbsp;</div>
            </td></tr></tfoot>
        </table>
    </body>