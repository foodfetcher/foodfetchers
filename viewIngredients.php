<div class="view-ingredients" >
    <div class="ingredients-display">
        <button type="button" class="back-button" onclick="closeIngredients(this)">
            &#5176; Back
        </button>
        <div class="ingredients-title">
            Grocery List
        </div>
        <button type="button" class="back-button" data-planname="<?php echo $planName;?>" onclick="printWindow(this)">
            Print
            <img class = "print-icon" src= "Images/print.png" alt="Print">
        </button>
        <div class="ingredients-section">
            <?php
               listIngredients($mealid, $db);
            ?>
        </div>
    </div>
</div>