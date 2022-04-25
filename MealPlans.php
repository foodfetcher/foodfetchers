<?php
	session_start();
	if (isset($_SESSION['userid']))
	{
		$log = "Logout";
    }
	else
	{
		$log = "Login";
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
    $delimeter ="#$%^&";
    
    function getIngredients($planId,$delimeter, $db){
        $res = pg_query($db, "SELECT ingredients FROM mealline INNER JOIN
        recipes ON mealline.recipeid=recipes.recipeid WHERE mealid=$planId
        ");
        echo pg_last_error($db);
        echo '<ul class="ingredients-list">';
        
            $groceryList = Array();
        while($row = pg_fetch_assoc($res)){
            $input = explode($delimeter,$row['ingredients'],PHP_INT_MAX);
            $numElt = false;
            $unitElt = false;
            foreach($input as $elt){
                $elt = ltrim($elt," ");
                $elt = rtrim($elt," ");
                if($numElt && $unitElt){
                    $match = false;
                    $newElt =   $unitElt." ".$elt;
                    foreach($groceryList as $exists => $total){
                        if(strtolower($exists) == strtolower($newElt)){
                            //echo '<br>';echo ' total: '; echo $elt ;echo $lastElt + $total. '<br>';
                           $groceryList[$exists] = $numElt + $total;
                            $match = true;
                            
                            break;
                        } 
                    }
                    if(!$match){
                    $groceryList = $groceryList + Array($newElt => $numElt );
                    }
                    $numElt = false;
                    $unitElt = false;
                    
                } elseif($numElt){
                    $unitElt = $elt;
                
                } else {
                $numElt = $elt;
                }
            }
        }
            foreach($groceryList as $ingredient => $quantity){
                echo '<li>' . $quantity . ' ' . $ingredient . '</li>';
            }
        echo '</ul>';
        
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Grocery List </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <style>
        #plan-display{
                width: 75vw;
                height:calc(90% - 80px);
                margin: 2.5vh auto 0 auto;
                background-color: var(--color2-darkGreyT);
                display:flex;
                flex-wrap: wrap;
                align-content: flex-start;
                overflow-y:auto;
                overflow-x:hidden;
                scroll-padding: .9vh 1vw;
                border-top: 1vh solid rgba(0,0,0,0);
                border-bottom: 1vh solid rgba(0,0,0,0);
                scrollbar-width: none;
            }
            .plan-title{
                width:100%;
                height:10%;
                padding: .5vh 1vw;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 2px;
            }
            .plan-title-text{
                font-size: min(5vh,4vw);
                margin: 0;
                background-color: var(--color1-white);
                width: calc(100% - 2vw);
                height: 100%;
                display: flex;
                justify-content: left;
                align-items: center;
                padding: 0 1vw;
            }
            .plan-title-text:after {
                content: "\002B";
                color: #777;
                font-weight: bold;
                margin-left: auto;
                align-items: center;
                display: flex;
            }
            .open{
                background: linear-gradient(0deg, var(--color1-white) 0%, rgb(233, 233, 233) 71%, rgb(228, 228, 228) 90%);
                box-shadow: 0 0 4px rgba(0,0,0,0.3);
            }
            .open:after {
                content: "\2212";
            }
            .buttons {
                height: 100%;
                width: 10%;
                border-radius: 0;
                border: 2px solid transparent;
                background-color:var(--color1-white);
                text-align: center;
                font-family: arial;
                color: black;
                text-decoration:none;
                font-size: 2vh;
                display:none;
            }
            .buttons p{
                margin:0;
            }
            .buttons:hover{
                border-color: var(--green);
                text-decoration:none;
            }
            .view-meal{
                height: 0;
                width:100%;
                transition: height .4s ease-out, width .4s ease-out;
            }
            .week-display {
                height:calc(90% - 3vh);
                display:flex;
                flex-direction: row;
                padding: 1vh 1vw;
                gap: 1vw;
                flex:1;
                overflow: hidden;
                //display:none;
            }
            .week-display h2{
                font-size: min(3.2vh,1.8vw);
                border-bottom: 1px solid black;
                padding: 0 0 1.25vh 0;
            }
            .week-display ol{
                padding: 0 0 0 .5vw;
                font-size: min(2.5vh,2vw);
                list-style-position: inside;
            }
            .day {
                height: calc(100% - 2vh );
                flex:1;
                background-color: var(--color1-white);
                box-shadow: 0 0 4px rgba(0,0,0,0.3);
                padding: 0vh .5vw; 1vh .5vw;
            }
            .view-ingredients{
                width:0;
                height:0;
                transition: height .4s ease-out, width .4s ease-out;
                overflow:hidden;
            }
            .ingredients-display{
                //width:100%;
                height: calc(100% - 2vh);
                margin: 1vh 1vw;
                background-color: var(--color1-white);
                display: flex;
                flex-flow: row wrap;
                align-content: flex-start;
            }
            .back-button{
                width: 10%;
                height: 10%;
                border: 0;
                font-size: min(3.2vh, 1.8vw);
                overflow: hidden;
                white-space: nowrap;
            }
            .print-icon{
                height: min(3.2vh, 1.8vw);
            }
            .ingredients-title{
                height:10%;
                width: calc(80% - 7vw);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: min(5.8vh, 2.5vw);
                overflow:hidden;
                margin: 2vh 3.5vw 1vh 3.5vw;
                border-bottom: 1px solid black;
            }
            .ingredients-section{
                height: calc(90% - 2vh);
                width:100%;
                padding: 3vh 0 3vh 6vw;
                overflow: hidden auto;
            }
            .ingredients-list{
                column-count:2;
                margin: auto;
                width: 75%;
                
            }
            .print-list{
                list-style: none;
                column-count: 1;
            }
            .print-list li{
                padding-left:.75vw;
            }
            .print-list li::marker{
                content:"\2610";
            }
            #bottom-spacer{
                height: calc(90% - 1vh);
                width:100%;
            }
            #new-plan{
                width: 10.5vw;
                background-color: var(--color1-white);
                margin: auto;
                position: fixed;
                right: 1.5vw;
                top: calc(80px + 2.5vh);
                display:flex;
                flex-direction: column;
                align-items: center;
                justify-content: end;
                overflow:hidden;
                font-size: min(1.25vw, 2.8vh);
                color:  #1a1a1a;
                text-align:center;
                text-decoration:none;
                box-shadow: 0 0 4px rgba(0,0,0,0.3);
            }
            #new-plan p{
                margin: 1vh 1vw;
            }
            #new-plan:hover #plus{
                color: var(--color2-darkGreyT);
            }
            #new-plan:hover {
                color: var(--color2-darkGreyT);
            }
            #plus{
                line-height: min(8.5vw, calc(75vh - 80px));
                font-size: min(10.5vw, calc(75vh - 80px));
                color: #777;
                
            }
            #scroll-image{
                position: fixed;
                width: 4vw;
                left: 88vw;
                bottom: 5.5vh;
                pointer-events: none;
                filter: invert(1);
                background-color: rgba(255,255,255,.4);
            }
            //#plan-display:hover #scroll-image{
                background-color: red;
            }
            .submit-clear{
                width: calc(100% - 2vw);
                height:10%;
                display:flex;
                flex-direction:row;
                padding: 0 1vw;
            }
            .submit-clear input{
                width:100%;
                height:100%; 
                font-size:2vh; 
                border-radius: 0;
                padding:0;
                border:0;
                background: linear-gradient(190deg, var(--color1-white) 0%, rgb(228, 228, 228) 71%, rgb(222, 222, 222) 90%);
                font-size: min(4vh, 2.5vw);
            }
            .submit-clear input:hover{
                background: rgb(210, 210, 210);
            }
            .print-page, .print-header, .print-footer{
                display:none;
            }
            @media print{
                @page { 
                    margin: 0; 
                }
                #background{
                    display:none;
                }
                .print-page{
                    display:block;
                }
                .header-pad{
                    height: 1.75in;
                    width:100%;
                }
                .footer-pad{
                    height: 1in;
                    width:100%;
                }
                .print-header{
                    position:fixed;
                    top:0;
                    padding: .5in .5in 0 .5in;
                    height: 1.25in;
                    display:block;
                }
                #print-title{
                    font-size:6vh;
                    border-bottom: 1.25px solid black;
                    width: 100vw;
                }
                #print-subtitle{
                    font-size: 3vh;
                    padding-left: 1vw;
                }
                #print-info{
                    padding-left: 5vw;
                }
                .print-footer{
                    position:fixed;
                    bottom:0;
                    margin: .5in .5in 0 .5in;
                    
                    height: .5in; width: calc(100vw - 1.05in); border-top: 1.25px solid black;
                    display:block;
                }
                .print-header {
                    
                }
                .print-footer {
                    padding: 0 .5in .5in .5in;
                }
                .print-footer img{
                    padding-top: .58vh;
                    width: 30%;
                    position:fixed;
                    left:.5in;
                }
            }
            @media screen and (max-width: 412px){
                #new-plan{
                    top: unset;
                    bottom: 1vh;
                    right: 12.5vw;
                    width: 75vw;
                }
                .view-ingredients{
                    height: calc(90% - 6vh);
                }
                .view-meal{
                    height: calc(90% - 6vh);
                }
                .week-display{
                    flex-direction: column;
                }
}
        </style>
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
                //viewMeal.firstElementChild.style.display ="none";
                viewMeal.style.height = null;
                viewIngredients.style.height = null;
                //viewMeal.style.width = null;
                //el.parentElement.style.padding = ".5vh 1vw";
            } else {
                el.classList.toggle("open");
                //viewMeal.style.width = "100%";
                viewMeal.style.height = "calc(90% - 1vh)";
                viewIngredients.style.height = "calc(90% - 1vh)";
                //el.parentElement.style.padding = "3vh 1vw";
                
                
                
            } 
            
        }
        function openIngredients(el){
            var viewMeal = el.parentElement.parentElement;//alert(viewMeal.className);viewMeal.style.backgroundColor ="blue";
            var viewIngredients = viewMeal.nextElementSibling;//alert(viewIngredients.className);
            viewMeal.style.width = "0";
            viewIngredients.style.width = "100%";
                /*
            if (viewMeal.offsetWidth){alert(viewMeal.offsetWidth);
                viewMeal.style.width = "0";
                viewIngredients.style.width = "100%";
            } else {
                viewMeal.style.width = "100%";
                //viewIngredients.style.height = null;
                viewIngredients.style.width = null;//alert(weekDisplay.offsetWidth);
            }*/
        }
        function closeIngredients(el){
            var viewIngredients = el.parentElement.parentElement;//alert(viewIngredients.className);
            var viewMeal = viewIngredients.previousElementSibling;//alert(viewMeal.className);
            viewMeal.style.width = "100%";
                //viewIngredients.style.height = null;
                viewIngredients.style.width = null;//alert(weekDisplay.offsetWidth);
        }
        function printWindow(el){
            var planId = el.getAttribute("data-planname");
            document.getElementById("print-subtitle").innerHTML = planId;
            var list = document.getElementById("print-info");
            list.innerHTML= el.nextElementSibling.innerHTML;
            list.firstElementChild.classList.toggle("print-list");//style.columnCount = "1";
            window.print();
        }
        </script>
    </head>
    <body>
        <div id = "background">
        <?php
            include 'nav.php'; //write out the nav bar
            include 'deleteModal.php';//delete confirmation modal
        ?> 
         <div id = "plan-display">
                
                        <?php
                
                                        $res = pg_query($db, "SELECT * FROM meals WHERE customerid=$userid
                                        ");
                                        echo pg_last_error($db);
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