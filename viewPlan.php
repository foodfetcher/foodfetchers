<div class="view-meal"  >
    <div class="week-display">
        <?php
            $weekDays = array("Sunday","Monday","Tuesday","Wednsday","Thursday","Friday","Saturday");
            foreach(array(0,1,2,3,4,5,6) as $day){
                $dayName = $weekDays[$day];
                echo "<div class='day'><h2>$dayName</h2><ol>";
                $resA = pg_query_params($db, "SELECT * FROM mealline WHERE day=$1 AND mealid=$2;", Array($day, $mealid));
                echo pg_last_error($db);
                while($rowA = pg_fetch_assoc($resA)){
                    $thisId = $rowA["recipeid"] ;
                    $result = pg_query_params($db, "SELECT * FROM recipes WHERE recipeid=$1;", Array($thisId));
                    $rrow = pg_fetch_assoc($result);
                    echo "<li>";
                    echo $rrow["recipename"] . "</li>";
                }
                        echo "</ol></div>";
            }      
        ?>   
    </div>
    <div class="submit-clear">
            <input type="button" value = "Delete Meal Plan"  onclick="document.getElementById('deleteModal').style.display='flex'" >
            <input type="button" value = "View Ingredients" onclick="openIngredients(this)" >
    </div>
</div>
