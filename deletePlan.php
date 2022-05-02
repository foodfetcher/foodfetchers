<script>
function clearModal(el){
    if(event.target.id == "deleteModal"){
        event.target.style.display = "none";
    }
}
function showModal(planId,planName){
    document.getElementById('deleteModal').style.display='flex';
    document.getElementById('trueDelete').value=planId;
    document.getElementById('saveName').value=planName;
}
function showOutcome(){
    document.getElementById('resultsTitle').innerHTML= 'resultsTitle'; 
}
function returnFromPage(){
    window.location.href = "MealPlans.php";
}
</script>
<style>
#colorText{
    color: var(--teal);
}
#deleteModal,
#resultsModal{
    display:none;
    position:fixed;
    z-index: 1;
    width:100%;
    height: 100%;
    background-color: rgba(0,0,0,.85);
    top: 0;
    left: 0;
}
#resultsModal{
    //display:flex;
}
#deletePrompt,
#resultsPrompt{
    margin: auto;
    background-color:  var(--color1-white);
    width: 50%;
    height: 35%;
    display: flex;
    flex-direction: column;
    justify-content:center;
    text-align:center;
    padding: 5vh 3vw;
}
#resultsPrompt{
    height: 20%;
}
#deleteTitle,
#resultsTitle{
    margin: 3vh 0;
    font-size: min(6vh, 3vw);
}
#deleteSubTitle,
#resultsSubTitle{
    margin: 0 0 3vh 0;
    font-size: min(3.2vh, 1.8vw);
    flex: 3;
}
#deleteSubTitle{
    margin: 0;
    flex: 3;
}
#closeX{
    position:absolute;
    left: 78%;
    bottom: 74.5%;
    font-size: min( 6vh, 4vw);
    font-weight: bold;
    color:  var(--color1-white);
}
#closeX:hover {
    cursor: pointer;
    color: #f44336;
}
#buttonContainer{
    display:flex;
    flex-flow: row wrap;
    height: 20%;
}
#deleteButton{
    background-color: #f44336;
}
#cancelButton{
    background-color: #ccc;
}
#deleteButton,
#cancelButton{
    border: none;
    border-radius: 0;
    cursor: pointer;
    width: 50%;
    opacity: .85;
    font-size: min(3.2vh, 1.8vw);
    height: 100%;
}
#deleteButton:hover,
#cancelButton:hover{
    opacity:1;
}
#results{
    display:none;
    position: fixed;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    background-color: rgba(0,0,0,.85);
    font-size: min(6vh, 3vw);
}
#results p{
    background-color:  var(--color1-white);
}
@media screen and (max-width: 412px){
    #deleteButton,
    #cancelButton{
        width: 100%;
    }
}
</style>
<form action="MealPlans.php" method="post">

<div id="deleteModal" onclick="clearModal(this)">
    <span id="closeX" onclick="document.getElementById('deleteModal').style.display='none'">
    &#10006;
    </span>
    <div id="deletePrompt">
        <h2 id="deleteTitle"> Delete MealPlan</h2>
        <p id="deleteSubTitle">Are you sure you want to delete this recipe?</p>
        <div id="buttonContainer">
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" id="cancelButton">Cancel</button>
            <input type="submit" value="Delete" onclick="showOutcome()" id="deleteButton">
        </div>
        <input type='hidden' name='delete' value='true'>
        <input id="trueDelete" type='hidden' name='mealid' value="">
        <input id="saveName" type='hidden' name='saveName' value="">
    </div>
</div>
</form>
<?php 
if(isset($deleteOutcome)){
    if(isset($_POST["delete"])){
        $resultMessage="deleted.";
    } else {
        $resultMessage="created.";
    }
    echo '<style>#resultsModal{display:flex;}</style>';
}
 ?>
<div id="resultsModal" onclick="returnFromPage()">
    <div id="resultsPrompt">
        <h2 id="resultsTitle"> Success, <span id="colorText"><?php echo $_SESSION["lastDeleted"]; ?></span> was <?php echo $resultMessage ?> </h2>
        <p id="resultsSubTitle">Click anywhere to continue...</p>
    </div>
</div>