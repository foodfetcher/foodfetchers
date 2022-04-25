<script>
function clearModal(el){
    if(event.target.id == "deleteModal"){
        event.target.style.display = "none";
    }
}
</script>
<style>
#deleteModal{
    display:none;
    position:fixed;
    z-index: 1;
    width:100%;
    height: 100%;
    background-color: rgba(0,0,0,.85);
    top: 0;
    left: 0;
}
#deletePrompt{
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
#deleteTitle{
    margin: 3vh 0;
    font-size: min(6vh, 3vw);
}
#deleteSubTitle{
    margin: 0;
    font-size: min(3.2vh, 1.8vw);
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
@media screen and (max-width: 412px){
    #deleteButton,
    #cancelButton{
        width: 100%;
    }
}
</style>
<div id="deleteModal" onclick="clearModal(this)">
    <span id="closeX" onclick="document.getElementById('deleteModal').style.display='none'">
    &#10006;
    </span>
    <form id="deletePrompt" action="">
        <h2 id="deleteTitle"> Delete Meal Plan</h2>
        <p id="deleteSubTitle"> Are you sure you want to delete this meal plan?</p>
        <div id="buttonContainer">
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" id="cancelButton">Cancel</button>
            <button type="button" onclick="document.getElementById('deleteModal').style.display='none'" id="deleteButton">Delete</button>
        </div>
    </form>
</div>