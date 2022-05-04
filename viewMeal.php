            <div class="view-meal">
        
                <div class="week-display">
                    <?php
                    
                    $weekDays = array("Sunday","Monday","Tuesday","Wednsday","Thursday","Friday","Saturday");
                    foreach(array(0,1,2,3,4,5,6) as $day){
                        $dayName = $weekDays[$day];
                        echo "<div class='day'><h2>$dayName</h2><div class='day-tile-content'>";
                        
                        $resA = pg_query_params($db, "SELECT * FROM mealline WHERE day=$1 AND mealid=$2;", Array($day, $mealid));
                        echo pg_last_error($db);
                        while($rowA = pg_fetch_assoc($resA)){
                            $thisId = $rowA["recipeid"] ;
                            $result = pg_query_params($db, "SELECT * FROM recipes WHERE recipeid=$1;", Array($thisId));
                            $rrow = pg_fetch_assoc($result);
                            //print_r($row);
                            if(!empty($rrow)){
                             echo '<a href="view.php?id='.$thisId.'" class ="meal-tile">
                                            <div class = "meal-tile-text">' . $rrow['recipename'] . '</div>
                                            <div class= "meal-tile-cover" ></div>';
                                            $ffilename = 'coverimages/' . $rrow['recipeid'];
												if (file_exists($ffilename)) {
													echo '<img  src="coverimages/' . $rrow['recipeid'] . '" alt="recipe cover image" style="width:100%;height:100%;object-fit:cover;">
                                        </a>';
												} else {
													echo '<img src="Images/logo.png" alt="recipe cover image" style="width:100%;height:100%;object-fit:cover;">
                                                </a>';}
                            }
                        }
                        echo "</div></div>";
                    }
                    
                    ?>
                    
                </div>
                <div class="submit-clear">
                        <input type="button" value = "Delete Meal Plan" onclick="showModal('<?php echo $mealid ?>','<?php echo htmlentities($planName, ENT_QUOTES | ENT_IGNORE); ?>')" >
                        <input type="button" value = "View Ingredients" onclick="openIngredients(this)" >
                </div>
            </div>