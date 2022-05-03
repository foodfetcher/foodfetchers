<script>
function clearModal(el){
    if(event.target.id == "deleteModal"){
        event.target.style.display = "none";
    }
}
function showModal(){
    document.getElementById('deleteModal').style.display='flex';
}
function showOutcome(){
    document.getElementById('resultsTitle').innerHTML= 'resultsTitle'; 
}
function returnFromPage(){
    window.location.href = "browse.php";
}
</script>
<style>
#colorText{
    color: var(--teal);
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

#deleteOpen{
    padding: 5px 16px;
    background-color: #eee;
    font-size: 16px;
    cursor: pointer;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 155px;
    margin: 0 4px 0 0px;
}
#deleteOpen:hover {
	background-color: #fff;
	border: 1px solid var(--green);
	border-bottom: 2px solid var(--green);
	padding-bottom: 4px;
}
#deleteOpen:active {
	background-color: var(--green);
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
<form action="create.php" method="post">
<input id="deleteOpen" type='button' value = 'Delete' onclick="showModal()">
<div id="deleteModal" onclick="clearModal(this)">
    <span id="closeX" onclick="document.getElementById('deleteModal').style.display='none'">
    &#10006;
    </span>
    <div id="deletePrompt">
        <h2 id="deleteTitle"> Delete Recipe</h2>
        <p id="deleteSubTitle">Are you sure you want to delete this recipe?</p>
        <div id="buttonContainer">
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" id="cancelButton">Cancel</button>
            <input type="submit" value="Delete" onclick="showOutcome()" id="deleteButton">
        </div>
        <input type='hidden' name='delete' value='true'>
        <input type='hidden' name='recipeid' value="<?php echo $recipeid ?>">
        <input id="saveName" type='hidden' name='saveName' value="<?php echo $recipeName ?>">
    </div>
</div>
</form>
