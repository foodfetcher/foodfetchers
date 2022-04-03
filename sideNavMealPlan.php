<nav id = "left-nav">
                <a class= "left-nav-tile" href=viewAccount.php>
                    <?php
                        $first = $_SESSION["firstname"];
                        echo "<div  style='padding: 2vh .5vw 2vh .5vw;' >Hello, $first</div>";
                    ?>
                </a>
                <a class= "left-nav-tile" href=browse.php >
                    <div style="border-top: 2px solid white">
                        Browse Recipes
                    </div>
                </a>
                <a class= "left-nav-tile" href=viewMeals.php >
                    <div  style="border-bottom: .7px solid white; border-top: .7px solid white">
                        New Meal Plan
                    </div>
                </a>
                <div class= "left-nav-tile" style="border-bottom: 2px solid white; pointer-events:none;">
                    <a>My Meal Plans</a>
                </div>
                <?php
                    $DB_HOST='localhost';
                    $DB_USER='fetcher1';
                    $DB_PASS='1234';
                    $DB_NAME='main'; 
                    $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                    
                    $userid = $_SESSION['userid'];
                    $res = pg_query($db, "SELECT * FROM meals WHERE customerid='$userid'");
                    while($row = pg_fetch_assoc($res)){
                        echo '<a  class = "left-nav-tile" href="viewMeal.php?id=' . $row["mealid"] . '"><div>' . $row['mealname'] . '</div></a>';
                    }
                    //pg_close($db);
                ?>
                <div class= "left-nav-tile" style="border-bottom: 2px solid white; border-top: 2px solid white">
                    <a>Grocery List</a>
                </div>
                
</nav>
<div id = "sidepad"></div>