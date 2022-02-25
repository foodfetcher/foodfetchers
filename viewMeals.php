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
        <style> body{min-height:100vh;}</style>
        <script>
            function allowDrop(ev) {
                ev.preventDefault();
            }

            function drag(ev) {
                ev.dataTransfer.setData("text/html", ev.target.id);
            }

            function drop(ev) {
                ev.preventDefault();
                var data = ev.dataTransfer.getData("text/html");
                var nodeCopy = document.getElementById(data).cloneNode(true);
                var dest=ev.target.id ;
                nodeCopy.addEventListener('click',addremove);
                nodeCopy.firstChild.nextSibling.nextSibling.nextSibling.style.backgroundImage = "url(Images/minus.png)";
                
                var newid = event.target.id.slice(0,event.target.id.length - 1);
                document.getElementById(newid+"P").appendChild(nodeCopy);
                document.getElementById(newid + "H").value += data.slice(0,data.length - 1);
            }
            
            function addremove(ev){
                var element = ev.target.parentElement;
                var targetId = event.target.parentElement.parentElement.id.slice(0,event.target.parentElement.parentElement.id.length - 1);
                element.remove();
                document.getElementById(targetId + "H").value="";
                var children = document.getElementById(targetId+"P").children;
                for (var i=2; i<children.length; i++){
                    document.getElementById(targetId + "H").value += children[i].id.slice(0,children[i].id.length - 1);
                }
            }
            function clearAll(){
                let days = ["day-tile-sun", "day-tile-mon", "day-tile-tues","day-tile-wed","day-tile-thurs","day-tile-fri","day-tile-sat"];
                days.forEach(clearDay);
            }
            function clearDay(value, index, array){
                var childs = document.getElementById(value + "P");
                var len =  childs.children.length;
                for (var i=2; i<len; i++){
                    childs.lastChild.remove();
                }
                document.getElementById(value + "H").value="";
            }
    </script>
    </head>
    <body>
		<div id = "background"></div>
        <div id = "back-container">
            
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        
        <div id = "content-container">
            <?php
                include 'sideNavMealPlan.php'; //write out the nav bar
            ?>
            <div id = "content-with-sidenav">
                <table id = "planner-table" style="width: 100%;"><!-- parent table-->
                <form method="post">
                    <tr><!-- table header(Meal Plan Name)-->
                        <th colspan = "2" style="background-color: white;border-radius:14px 14px 0 0;border-bottom:4px solid #38817a; padding: 2vh 0 2vh 0">
                            <label for="mealname" style="font-size:2.5vh; font-family: Ubuntu;">Meal Plan Name:</label>
                            <input type="text" name="mealname" placeholder="My Meal Plan" style="height:2.5vh; font-size:2.5vh;" required>
                        </th>
                    </tr><!-- END: table header(Meal Plan Name)-->
                    <tr><!-- column headers -->
                            <td>
                                <h1 style="font-size:2.5vh; font-family: Ubuntu;background-color: #dbf9d1; border-right:2px solid #38817a ;border-left:2px solid #38817a ;margin:0; padding: 0;">My Meals</h1>
                            </td>
                            <td>
                                <h1 style="font-size:2.5vh; font-family: Ubuntu;background-color: #dbf9d1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;margin:0; padding: 0;;">My Plan</h1>
                            </td>
                    </tr><!-- END: column headers -->
                    <tr><!-- content row -->
                    <td style="width:33.33%;vertical-align:top;border-right:2px solid #38817a ;border-left:2px solid #38817a ;"><!-- left column (favorited recipes)-->
                        <div style="width: 100%;height:100%;display:flex;flex-wrap:wrap;justify-content: center;padding:0;margin:0;"><!-- left table (favorited recipes)-->
                            
                            <!-- fill in rows of table with fav recipes-->
                            <?php
                                if($_SERVER['REQUEST_METHOD'] === 'GET'){
                                        $_SESSION["lastQuery"] = $_POST;
                                        $DB_HOST='localhost';
                                        $DB_USER='fetcher1';
                                        $DB_PASS='1234';
                                        $DB_NAME='main'; 
                                        $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                                        //echo "myrecipes";
                                        $userid = $_SESSION['userid'];
                                        $res = pg_query($db, "SELECT * FROM favorites
                                        INNER JOIN recipes ON favorites.recipeid=recipes.recipeid 
                                        WHERE favorites.userid='$userid';");
                                        echo pg_last_error($db);
                                        while($row = pg_fetch_assoc($res)){
                                        echo '<div id= "'. $row['recipeid'] .'C" class ="meal-tile"  draggable="true" ondragstart="drag(event)">
                                            <div id= "'. $row['recipeid'] .'C" class = "meal-tile-text" style="width:100%;position:absolute;text-align:center;background-color: rgba(244,244,244,.8)">' . $row['recipename'] . '</div>
                                            <div id= "'. $row['recipeid'] .'C" class= "meal-tile-cover" ></div>
                                            <img id= "'. $row['recipeid'] .'C" src="coverimages/' . $row['recipeid'] . '" alt="recipe cover image" style="max-width:100%;max-height:100%;object-fit:cover;"/>
                                        </div>';
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
                                        pg_query($db, "INSERT INTO mealline (mealid, recipeid, day) VALUES ($mealid, $item, $day);");
                                        echo pg_last_error($db) . "<br/>";
                                        echo "inserted $item to $day <br/>";
                                    }
                                }  
                                pg_close($db);
                            }
                                ?>
                        </div>
                    </td>
                    <td style="width:66.66%"><!-- right column (meal plan)-->
                        <!--<table style="width:100%; border: 2px solid chartreuse;"> right table (meal plan)-->
                        <!-- form for positng new meal plan -->
                        
                            <table style = "width: 100%"><!-- form table (week of meal plan) -->
                                <tr><td style="background-color:#dbf9d1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;">
                                    <div id ="day-tile-sunP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-sunH" class="hiddenInput" type="hidden" name="sunday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-sunC" class="day-tile-lable" style="background-color:#dbf9d1;">Sunday</p>
                                    </div>
                                </td></tr>
                                <tr><td style="background-color:#bee5c1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;">
                                    <div id ="day-tile-monP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-monH" class="hiddenInput" type="hidden" name="monday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-monC" class="day-tile-lable" style="background-color:#bee5c1;">Monday</p>
                                    </div>
                                </td></tr>
                                <tr><td style="background-color:#dbf9d1;border-right:2px solid #38817a ;border-left:2px solid #38817a ; ">
                                    <div id ="day-tile-tuesP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-tuesH" class="hiddenInput" type="hidden" name="tuesday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-tuesC"  class="day-tile-lable" style="background-color:#a3d0b1">Tuesday</p>
                                    </div>
                                </td></tr>
                                <tr><td style="background-color:#bee5c1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;">
                                    <div id ="day-tile-wedP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-wedH" class="hiddenInput" type="hidden" name="wednesday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-wedC"  class="day-tile-lable"style="background-color:#87bca3">Wednesday</p>
                                    </div>
                                </td></tr>
                                <tr><td style="background-color:#dbf9d1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;">
                                    <div id ="day-tile-thursP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-thursH" class="hiddenInput" type="hidden" name="thursday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-thursC" class="day-tile-lable" style="background-color:#6da895">Thursday</p>
                                    </div>
                                </td></tr>
                                <tr><td style="background-color:#bee5c1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;">
                                    <div id ="day-tile-friP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-friH" class="hiddenInput" type="hidden" name="friday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-friC"  class="day-tile-lable" style="background-color:#539587">Friday</p>
                                    </div>
                                </td></tr>
                                <tr><td style="background-color:#dbf9d1;border-right:2px solid #38817a ;border-left:2px solid #38817a ;">
                                    <div id ="day-tile-satP" class= "day-tile" ondrop="drop(event)" ondragover = "allowDrop(event)">
                                        <input id = "day-tile-satH" class="hiddenInput" type="hidden" name="saturday" placeholder="1 2 3 4" required>
                                        <p id="day-tile-satC"  class="day-tile-lable" style="background-color:#38817a">Saturday</p>
                                    </div>
                                </td></tr>
                            </table><!-- END: form table (week of meal plan) -->
                    </td>
                    </tr><!-- END: content row -->
                    <tr>
                    <td colspan="2">
                        <input type="submit" value = "Submit" class = "seventh"  style="width:100%; border-radius:0 0 0 0; border:2px solid #38817a; border-bottom: 0px;" >
                        <input type="reset" value = "Clear" class = "seventh" onclick="clearAll()" style="width:100%;border-radius: 0 0 14px 14px ; border:2px solid #38817a; border-top: 2px solid grey;">
                    </td>
                    </tr>
            </form>
                </table><!-- END: parent table-->
            </div>
        </div>
        </div>
    </body>
</html>
