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
    
    header("cache-control: private");
    header("pragma: private");
?>
<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Meal Planner </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <style>
        /* Colors */
            :root {
                --color1-white: rgb(244,244,244);
                --color2-darkGreyT: rgba(0,0,0,.35);
                --color3-lightGrey: rgb(225,225,225);
                --color4: #38817a;
            }
        /* meal planner*/
            #back-container{
                display: flex;
                flex-direction: column;
                height: calc(100vh );
                width: calc(100vw - var(--scrollbar-width));
                background-image:url(Images/background3.jpg);
                background-size:cover;
            }
            #content-container{
                display: flex;
                flex-direction: row;
                flex: 1;
                min-height: calc(100vh - 80px);
            }
            #new-plan-form {
                display:flex;
                flex-wrap: wrap;
                flex-direction:column;
                margin:  auto auto auto auto;
                width: 85vw;
                height: 88vh;
                box-shadow: 0 0 4px rgba(0,0,0,0.3);
            }  
        /* meal tile: element for each meal with image and name */
            .meal-tile{
                border: 0px solid var(--teal);
                position: relative;
                background-color: var(--color1-white);
                height: 14vh;
                width: 28vh;
                text-align: center;
                align-items: center;
                cursor: pointer;
                flex-shrink:0;
                transform: rotate(90deg);
                box-shadow: 0 0 8px rgba(0,0,0,0.7);   
                margin: 7vh 0 calc(7vh + 1vw);
            }
            .meal-tile:hover{
                border: 1px solid grey;
            }
            .meal-tile:hover div.meal-tile-cover{
                display: block;
            }
            .meal-tile-text{
                width: 100%;
                position: absolute;
                text-align: center;
                background-color: rgba(244,244,244,.8);
            }
            .meal-tile-cover{
                display: none;
                position: absolute;
                background-image: url(Images/plus.png);
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: 100% 100%;
                height: 100%;
                width: 100%;
            }
        /* new meal name input and label */
            #new-meal-name {
                display:flex;
                height: 15%;
                width: 100%;
                align-items:center;
                justify-content:center;
                background: linear-gradient(212deg, var(--color1-white) 0%, rgb(233, 233, 233) 71%, rgb(228, 228, 228) 90%);
            }
        /* favorite meals display and label */
            #fav-meals{
                background-color:var(--color2-darkGreyT);
                height:18%;
                display:flex;
                flex-direction:row;
                flex-shrink:0;
            }
            #fav-meals-title{
                width: 15%;
                font-size:2.5vh; 
                margin:0;
                flex-shrink:0;
                writing-mode: vertical-lr;
                transform: rotate(180deg);
                display:flex;
                background: linear-gradient(32deg, var(--color1-white) 0%, rgb(233, 233, 233) 71%, rgb(228, 228, 228) 90%);
            }
            #fav-meals-title h1{
                align-self: flex-end;
                text-align: center;
                height: 100%;
                margin: .25vw;
                font-size: 1.25vw;
            }
            #fav-meals-display{
                width: 16.45vh;
                height:80vw;
                display:flex;
                flex-direction:column;
                flex-shrink:0;
                align-items:center;
                align-content:flex-start;
                overflow-y:auto; 
                overflow-x:hidden;
                position:relative;
                transform-origin: right top;
                left: calc(-16.1vh - 9.25vw);
                transform: rotate(-90deg);
                scrollbar-width:none;
            }
            #fav-meals-display::-webkit-scrollbar {
                display: none;
            }
            #fav-meals-display-gradient{
                background: linear-gradient( rgba(0,0,0,.6) 0%, rgba(255,255,255,0) 5%, rgba(255,255,255,0) 12%);
                pointer-events: none;  
                position:relative;
                transform-origin: right top;
                left: calc(-30.62vh - 9.25vw);
                transform: rotate(-90deg);    
                width: 14.45vh;
                height:80vw;           
                margin-top: 1.1vh;
                border-top: 1px solid black;
            }
        /* Weekly schedule with days */
            #schedule{
                height:61%;   
                background-color: var(--color2-darkGreyT);
                display:flex;
                flex-direction:row;
            }
            #schedule-title{
                width: 15%;
                font-size:2.5vh; 
                margin:0;
                flex-shrink:0;
                writing-mode: vertical-lr;
                transform: rotate(180deg);
                display:flex;
                background: linear-gradient(32deg, var(--color1-white) 0%, rgb(233, 233, 233) 71%, rgb(228, 228, 228) 90%);
            }
            #schedule-title h1{
                align-self: flex-end;
                text-align: center;
                height: 100%;
                margin: .25vw;
                font-size: 1.25vw;
            }
            #schedule-content{
                width: 94%;
                display:flex;
                flex-direction: row;
                gap:1vw;
                margin: 1vw;
                left: -10vw;
                position: relative;
                flex-shrink:0;
            }
            .day-tile{
                display: flex;
                flex-direction:column;
                background-color: var(--color1-white);
                flex:1;
                box-shadow: 0 0 8px rgba(0,0,0,0.7);
            }
            .day-tile-lable{
                height: 8.5vh; 
                width: calc(100% - .5vw);
                text-align: center;
                margin: 0 .25vw;
                display: flex;
                text-align: center;
                align-items: center;
                justify-content: center;
                cursor:default;
                border-bottom: 1px solid black;
                flex-shrink: 0;
            }
            .day-tile-content{
                flex-grow: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                overflow-y:auto;
                overflow-x:hidden;
            }
            #submit-clear{
                width:100%;
                height:6%;
                display:flex;
                flex-direction:row;
            }
            #submit-clear input{
                width:100%;
                height:100%; 
                font-size:2vh; 
                border-radius: 0;
                padding:0;
            }
            .back-button{
                width: 6%;
                height: 6%;
                border: 0;
                font-size: min(4vh, 1.3vw);
                overflow: hidden;
                background-color: rgba(244,244,244,.6);
                position: fixed;
                top: calc(80px + 2.3vh);
                left: .5vw;
                padding-right: .5vw;
                box-shadow: 0 0 4px rgba(0,0,0,0.3);
            }
            .back-button:hover{
                background-color: rgba(244,244,244,.95);
            }
        </style>
        <script>
            var newID=0;
            
            function allowDrop(ev) {
                ev.preventDefault();
            }

            function drag(ev) {
                ev.dataTransfer.setData("text/html",ev.target.id);
                var drgImg = new Image();
                drgImg.src = "Images/miniLogo.png";
                ev.dataTransfer.setDragImage(drgImg,0,0);
            }

            function drop(ev) {
                ev.preventDefault();
                var data = ev.dataTransfer.getData("text/html");
                //clone dragged node
                var nodeCopy = document.getElementById(data).cloneNode(true);
                //prevent duplicate id on cloned objects
                nodeCopy.id = "clone" + newID++;
                //change add to remove
                nodeCopy.firstChild.nextSibling.nextSibling.nextSibling.style.backgroundImage = "url(Images/minus.png)";
                //add event listener for remove
                nodeCopy.addEventListener('click',removeElt);
                //get data-parentObj attribute from target
                var dest = ev.target.parentElement.getAttribute("data-parentObj");
                if(dest == null){
                    dest = ev.target.getAttribute("data-parentobj");
                }
                //set data-parentObj attribute for new node
                nodeCopy.setAttribute("data-parentObj",dest);
                //add recipeid to hidden input
                document.getElementById(dest+"Hidden").value += nodeCopy.getAttribute("data-recipeid") + " ";
                //add nodeCopy to destination
                document.getElementById(dest+"Content").appendChild(nodeCopy);
                //Style new Node
                nodeCopy.style.height= "7.25vh";
                nodeCopy.style.transform= "rotate(0deg)";
                nodeCopy.style.width= "100%";
                nodeCopy.style.fontSize= "2vh";
                nodeCopy.style.margin= "0";
            }
            
            function removeElt(ev){
                //element being removed
                var element = ev.target.parentElement;
                element.remove();
                //get data-parentObj of element being removed
                var targetParent = ev.target.parentElement.getAttribute("data-parentObj");
                //clear input of parent
                document.getElementById(targetParent + "Hidden").value="";
                //refill input with remaining elements
                var children = document.getElementById(targetParent+"Content").children;
                for (var i=0; i<children.length; i++){
                    document.getElementById(targetParent + "Hidden").value += children[i].getAttribute("data-recipeid") + " ";
                }
            }
            function clearAll(){                           //clear all for each day
                let days = ["Sunday", "Monday", "Tuesday","Wednesday","Thursday","Friday","Saturday"];
                days.forEach(clearDay);
            }
            function clearDay(value, index, array){        //clear content and input
                var content = document.getElementById(value + "Content");
                var len =  content.children.length;        //number of nodes in this day's content
                for (var i=0; i<len; i++){                 //remove all children nodes in this day's content
                    content.lastChild.remove();
                }
                document.getElementById(value + "Hidden").value="";//clear input
            }
        </script>
    </head>
    <body>
	    <div id = "background">
            
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        
        <div id = "content-container">
        <button type="button" class="back-button" onclick="window.location.href='MealPlans.php';">
            &#5176; Back
        </button>
            <form id="new-plan-form" method="post">
                <div id="new-meal-name">
                    <label for="mealname" style="font-size:2.5vh; ">Meal Plan Name: &nbsp;</label>
                    <input type="text" name="mealname" placeholder="My Meal Plan" style="height:2.5vh; font-size:2.5vh;" required>
                </div>
                <div id="fav-meals">
                    <div id="fav-meals-title" style="">
                        <h1>My Meals</h1>
                    </div>
                    <div id="fav-meals-display" ><!--scrollable container for meal tiles-->
                        <!-- create a tile for each favorited meal-->
                        <?php
                                if($_SERVER['REQUEST_METHOD'] === 'GET'){
                                        $_SESSION["lastQuery"] = $_POST;
                                        $DB_HOST='localhost';
                                        $DB_USER='fetcher1';
                                        $DB_PASS='1234';
                                        $DB_NAME='main'; 
                                        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                                        $userid = $_SESSION['userid'];
                                        $res = pg_query($db, "SELECT * FROM favorites
                                        INNER JOIN recipes ON favorites.recipeid=recipes.recipeid 
                                        WHERE favorites.userid='$userid';");
                                        echo pg_last_error($db);
                                        while($row = pg_fetch_assoc($res)){
                                        echo '<div id= "'. $row['recipeid'] .'" data-recipeid="'. $row['recipeid'] .'"  class ="meal-tile" draggable="true" ondragstart="drag(event)">
                                            <div class = "meal-tile-text">' . $row['recipename'] . '</div>
                                            <div class= "meal-tile-cover" ></div>';
                                            $filename = 'coverimages/' . $row['recipeid'];
												if (file_exists($filename)) {
													echo '<img src="coverimages/' . $row['recipeid'] . '" alt="recipe cover image" style="width:100%;height:100%;object-fit:cover;"/>
                                        </div>';
												} else {
													echo '<img src="Images/logo.png" alt="recipe cover image" style="width:100%;height:100%;object-fit:cover;"/>
                                        </div>';}
                                        }
                                        pg_close($db);
                        
                            }
                            else if($_SERVER['REQUEST_METHOD'] === 'POST'){
                                $_SESSION["lastQuery"] = $_POST;
                                $DB_HOST='localhost';
                                $DB_USER='fetcher1';
                                $DB_PASS='1234';
                                $DB_NAME='main'; 
                                $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                                $userid = $_SESSION['userid'];
                                $mealname = $_POST["mealname"];
                                $timestamp = date('Y-m-d H:i:s');
                                $mealid = pg_fetch_assoc(pg_query($db, "INSERT INTO meals (mealname, customerid, creationdate) VALUES ('$mealname', $userid, '$timestamp') RETURNING mealid;"))["mealid"];
                        
                                echo pg_last_error($db) . "<br/>";
                                $week = array(explode(" ", $_POST["sunday"], PHP_INT_MAX),
                                explode(" ", $_POST["monday"], PHP_INT_MAX),
                                explode(" ", $_POST["tuesday"], PHP_INT_MAX),
                                explode(" ", $_POST["wednesday"], PHP_INT_MAX),
                                explode(" ", $_POST["thursday"], PHP_INT_MAX),
                                explode(" ", $_POST["friday"], PHP_INT_MAX),
                                explode(" ", $_POST["saturday"], PHP_INT_MAX));
                        
                                foreach($week as $day=>$arr){
                                    foreach($arr as $item){
                                        if($item != ""){
                                            pg_query($db, "INSERT INTO mealline (mealid, recipeid, day) VALUES ($mealid, $item, $day);");
                                            //echo pg_last_error($db) . "<br/>";
                                            //echo "inserted $item to $day <br/>";
                                        }
                                    }
                                }  
                                
                                        $res = pg_query($db, "SELECT * FROM favorites
                                        INNER JOIN recipes ON favorites.recipeid=recipes.recipeid 
                                        WHERE favorites.userid='$userid';");
                                        echo pg_last_error($db);
                                        while($row = pg_fetch_assoc($res)){
                                        echo '<div id= "'. $row['recipeid'] .'B" class ="meal-tile" draggable="true" ondragstart="drag(event)">
                                            <div id= "'. $row['recipeid'] .'T" class = "meal-tile-text">' . $row['recipename'] . '</div>
                                            <div id= "'. $row['recipeid'] .'C" class= "meal-tile-cover" ></div>';
                                            $filename = 'coverimages/' . $row['recipeid'];
												if (file_exists($filename)) {
													echo '<img id= "'. $row['recipeid'] .'I" src="coverimages/' . $row['recipeid'] . '" alt="recipe cover image" style="width:100%;height:100%;object-fit:cover;"/>
                                        </div>';
												} else {
													echo '<img id= "'. $row['recipeid'] .'I" src="Images/logo.png" alt="HERE recipe cover image" style="width:100%;height:100%;object-fit:cover;"/>
                                        </div>';}
                                        }
                                pg_close($db);
                            }
                                ?>
                                
                        </div>
                        <div id="fav-meals-display-gradient"></div>
                </div>
                <div id="schedule">
                    <div id="schedule-title">
                        <h1>My Plan</h1>
                    </div>
                    <!-- Weekly Schedule for Meal Plan Form -->
                    <div id = "schedule-content" >
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Sunday">
                            <input id = "SundayHidden" class="hiddenInput" type="hidden" name="sunday" data-parentObj="Sunday">
                            <p class="day-tile-lable" data-parentObj="Sunday">Sunday</p>
                            <div id="SundayContent" class="day-tile-content" data-parentObj="Sunday"></div>
                        </div>
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Monday" >
                            <input id = "MondayHidden" class="hiddenInput" type="hidden" name="monday" data-parentObj="Monday">
                            <p class="day-tile-lable" data-parentObj="Monday">Monday</p>
                            <div  id="MondayContent" class="day-tile-content" data-parentObj="Monday"></div>
                        </div>
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Tuesday">
                            <input id = "TuesdayHidden" class="hiddenInput" type="hidden" name="tuesday" data-parentObj="Tuesday">
                            <p class="day-tile-lable" data-parentObj="Tuesday">Tuesday</p>
                            <div id="TuesdayContent" class="day-tile-content" data-parentObj="Tuesday"></div>
                        </div>
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Wednesday">
                            <input id = "WednesdayHidden" class="hiddenInput" type="hidden" name="wednesday" data-parentObj="Wednesday">
                            <p class="day-tile-lable" data-parentObj="Wednesday">Wednesday</p>
                            <div id="WednesdayContent" class="day-tile-content" data-parentObj="Wednesday"></div>
                        </div>
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Thursday">
                            <input id = "ThursdayHidden" class="hiddenInput" type="hidden" name="thursday" data-parentObj="Thursday">
                            <p class="day-tile-lable" data-parentObj="Thursday">Thursday</p>
                            <div id="ThursdayContent" class="day-tile-content" data-parentObj="Thursday"></div>
                        </div>
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Friday">
                            <input id = "FridayHidden" class="hiddenInput" type="hidden" name="friday" data-parentObj="Friday">
                            <p class="day-tile-lable" data-parentObj="Friday">Friday</p>
                            <div id="FridayContent" class="day-tile-content" data-parentObj="Friday"></div>
                        </div>
                        <div class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)" data-parentObj="Saturday">
                            <input id = "SaturdayHidden" class="hiddenInput" type="hidden" name="saturday" data-parentObj="Saturday">
                            <p class="day-tile-lable" data-parentObj="Saturday">Saturday</p>
                            <div id="SaturdayContent" class="day-tile-content" data-parentObj="Saturday"></div>
                        </div>
                    </div>
                    <!-- END: Weekly Schedule for Meal Plan Form -->
                </div>
                <!-- Buttons for Submit and Clear -->
                <div id="submit-clear">
                    <input type="submit" value = "Create Meal Plan"   >
                    <input type="reset" value = "Clear Plan" class = "seventh" onclick="clearAll()">
                </div>
                <!-- END: Buttons for Submit and Clear -->
            </form>
        </div>
        </div>
    </body>
</html>
