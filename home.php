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

<html>
    <head>
        <title> Food Fetchers | home </title>
        <link rel="stylesheet" href="phaseIstyle.css">
    </head>
    <body>
        <?php
            include 'nav.php'; //write out the nav bar
        ?> 
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
        </div>
        <a href = signup.php><div id = "interested">
            <h1> INTERESTED? CLICK HERE TO GET STARTED! </h1>
        </div></a>
    </body>
</html>
