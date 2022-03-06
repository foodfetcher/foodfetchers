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
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Food Fetchers | Home </title>
        <link rel="stylesheet" href="phaseIstyle.css">
        <style>
            /* alt header for home page*/
            #header {
                display:flex;
                font-size: 2vh;
                height: 6vh;
                padding: 0 2vw;
                position:fixed;
                width:calc(100% - 27px);
                background-color: rgba(0,0,0,0);
                box-shadow: 0 0 0 0;
            }
            #logo {
                display:none;
            }

            #nav {
                display:flex;
                    height: 100%;
                width: 100%;
                justify-content:right;
                align-items:center;
            }

            #nav a {
                color: #333;
                text-decoration: none;
                transition: 200ms;
                padding: 2vh 3vw;
            }

            #nav a:hover {
                color: #fff;
                background-color: rgba(0,0,0,.3);
            }
            #signup {
                background-color: var(--green);
                margin: 0 2vw 0 0;
            }
            #logout {
                padding: 0 2.5vw 0 .5vw;
            }

            #logout a {
                padding: 2px;
                cursor: pointer;
                border-radius: 0px;
            }
            #background {
                background-image:url(Images/background1.jpg);
            }
            /* Main Title, Sub Title*/
            #intro {
                height: calc(92vh - 90px); 
                display:flex; 
                flex-direction:column; 
                justify-content: center;align-items: left;
                padding:2vw; 
                background-color: rgba(0,0,0,.3); 
                width:50vh;
            }
            #title{
                text-align: left;
                color:white;
                font-size: 11.5vh; 
                text-shadow: 2px 2px 9px #000000; 
                margin:0;
            }
            #sub-title{
                text-align: left;
                color:white;
                font-size: 3vh; 
                text-shadow: 2px 2px 3px #000000;
            }
            /* Interested Banner (Call to Action) */
            #interested {
                align-items:center;
                background-color: rgba(255,255,255,0.9);
                box-shadow: 0 0 6px rgba(0,0,0,0.7);
                bottom: 0;
                margin-bottom: 20px;
                padding: 4px 10px;
                text-align: center;
                color: black;
                cursor: pointer;
                transition: background-color 200ms;
                width: 45vh;
            }
            #interested:hover {
                background-color: var(--lightgreen);
                transition: 0ms;
                border: 1px solid var(--green);
                margin-bottom: 19px;
            }
            #interested:active {
                background-color: var(--green);
            }
            #interested p {
                color: #333333;
                font-size: 1.75vh;
                margin:0;
                padding: .45vh 0vw .1vh 0vw;
            }
            #interested a {
                color: black;
                text-decoration: none;
            }
            
            /* Plate Animation*/
            #info:hover #plate1{
                position: relative;
                animation: image 4s forwards;
  
                animation-delay:.5s;
            }
            #info-cover{
                display:none;
            }
            #info:hover #info-cover{
                animation: cover 4s forwards;
                animation-delay:.5s;
            }
            @keyframes image {
                0%   { transform: rotate(0deg); left:-10vw; top:0px;}
                100% { transform: rotate(180deg); left:80vw; top:0px;}
            }
            @keyframes cover {
                0%   { left:-100vw; top:0px;}
                100% { left:0vw; top:0px;}
            }

    </style>
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id="intro" style="">
            <p id="title">
                Fast.<br>Simple.<br>Delicious.<br>
            </p>
            <p id="sub-title">
                The<br>Perfect<br>Meal Planner<br>For You
            </p>
            <?php
			if ($log == "Login"){ 
				echo '<a href = signup.php style="text-decoration: none;"><div id = "interested">
					<p> Click Here to Try Free! </p>
					</div></a>';
			};
            ?>
        
        </div>
        
        <div id="border-line" style="height: 1.5vh; background-color:var(--green);"></div>
        <div id="info" style="height: 50vh;  position:relative;display:flex; flex-direction:row;align-items: left; background-color:white; overflow: hidden; padding:0; margin:0;">
            <div id="info-cover" style="position:absolute;height: 100%; width:100vw;display:flex; flex-direction:column; justify-content: center;align-items: center; right:100vw ;background-color:white;">
                <div >
                <table style="border-spacing: 4vh">
                <tr style="">
                <td style="border-left: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                <td>
                <p style="font-size:2.5vh;color:#333333;">Upload your <br> favorite recipes</p>
                </td>
                <td style="border-right: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                <td style="border-left: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                <td>
                <p style="font-size:2.5vh;color:#333333;">Browse our collection of hundreds<br> of recipes to find meals that <br>satisfy your diet and your apitite</p>
                </td>
                <td style="border-right: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                
                </tr>
                <tr style="padding: 2vh">
                <td style="border-left: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                <td>
                <p style="font-size:2.5vh;color:#333333;">Schedule your meals <br> to make eating simple and easy<br> so you can focus on <br>enjoying the food that you make.</p>
                </td>
                <td style="border-right: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                <td style="border-left: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                <td>
                <p style="font-size:2.5vh;color:#333333;">Automaticly create a grocery list<br> to take the stress out of shopping. <br>Always be prepared with the ingredients you need.</p>
                </td>
                <td style="border-right: 4px solid green; border-top: 4px solid green; border-bottom: 4px solid green; "></td>
                
                </tr>
                </table>
                </div>
            </div>
            <img id="plate1" height="100%" src="Images/plate1.png" style=" position: relative;right: 10vw; margin:0 -10vw 0 0;">
            <div id="info-title" style="height: 100%; display:flex; flex-direction:column; justify-content: center;align-items: center; ">
                <div >
                <p style="text-align: left;color:#333333;font-size: 5vh; margin: 0vh 2vw;border-bottom: 3px solid green">
                Plan For a Healthier You
                </p>
                <p style="text-align: left;color:#333333;font-size: 2vh; margin: 0vh 2vw;">
                create customized meal plans tailored to your lifestyle, budget and tastes
                </p>
                </div>
            </div>
            
        </div>
        <!--
        <div id = "Content">
            <h1> Fast, Easy Meals. Perfect For You! </h1>
            <table style="table-layout: fixed; width: 100%;">
                <tr>
                    <td style="width: 33.33%;">
                        <h3> Choose from HUNDREDS of personalized recipes to fit YOUR diet! </h3>
                    </td>
                    <td style="width: 33.33%;">
                        <h3> Customize your MEAL PLANS to your diet, budget, and tastes!​ </h3>
                    </td>
                    <td style="width: 33.33%;">
                        <h3> Get ingredients delivered through INSTACART straight to your home! </h3>
                    </td>
                </tr>
            </table>
        </div>-->
		
    </body>
</html>
