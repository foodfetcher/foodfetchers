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
			#headpad {
				height: 0px;
			}
			#background {
				background-image:url(Images/background1.jpg);
			}
		</style>
    </head>
    <body>
		<div id = "background"></div>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
        <div id="intro">
            <p id="title">
                Fast.<br>Simple.<br>Delicious.<br>
            </p>
            <p id="sub-title">
                <i>The Meal Planner Made For You</i>
            </p>
            <?php
				if ($log == "Login"){ 
					echo '<a href = signup.php><div id = "interested">
						<p> Click Here to Get Started! </p>
						</div></a>';
				};
            ?>
        </div>
        
        <div id="border-line" style="height:1.5vh; background-color:var(--green);"></div>
        <div id="info">
            <div id="info-cover">
                <div>
					<table style="border-spacing: 4vh; font-size: 2.5vh; color: #333;">
						<tr>
							<td style="border: 4px solid var(--green); border-right: 0;"></td>
							<td><p>Upload your own <br>delicious recipes.</p></td>
							<td style="border: 4px solid var(--green); border-left: 0;"></td>
							
							<td style="border: 4px solid var(--green); border-right: 0;"></td>
							<td><p>Browse our collection of dozens <br>of tasty recipes to find meals that <br>satisfy your diet and your appetite.</p></td>
							<td style="border: 4px solid var(--green); border-left: 0;"></td>
						</tr>
						
						<tr style="padding: 2vh">
							<td style="border: 4px solid var(--green); border-right: 0;"></td>
							<td><p>Schedule your meals <br>to spend <b>less time planning</b> <br>and <b>more time enjoying</b><br> the food you create.</p></td>
							<td style="border: 4px solid var(--green); border-left: 0;"></td>
							
							<td style="border: 4px solid var(--green); border-right: 0;"></td>
							<td><p>We'll fill out your grocery lists <br>to ensure you're <b>always prepared</b> <br>with every ingredient you need.</p></td>
							<td style="border: 4px solid var(--green); border-left: 0;"></td>
						</tr>
					</table>
                </div>
            </div>
            <img id="plate1" height="100%" src="Images/plate1.png">
            <div id="info-title">
                <div>
					<p style="font-size: 5vh; border-bottom: 3px solid var(--green);">Plan for a Healthier You</p>
					<p style="font-size: 2.5vh;">Create customized meal plans tailored to your lifestyle, budget and tastes</p>
                </div>
            </div>
        </div>
    </body>
</html>
