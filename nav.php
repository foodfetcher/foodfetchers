<?php
    echo '<div id = "Navigation">
    <table style = "float:left">
        <tbody>
            <tr>
                <td>
                    <a href = home.php><img src ="Images/logo.png" width = "100" height = "90" alt="logo"></a>
                </td>
            </tbody>
        </table>
        
        <a href = home.php>Home</a> &nbsp;
        ';
            if($log == "Login"){
                echo "<a href = signup.php>Sign Up</a> &nbsp;";
            }
        echo '
        <a href = browse.php>Browse Recipes</a> &nbsp;
        <a href = info.php>Info</a> &nbsp;
        ';
            if($log == "Logout"){
                $first = $_SESSION["firstname"];
                $last = $_SESSION["lastname"];
                echo "<a href = create.php>Create Recipe</a> &nbsp;";
                echo "<a href = viewMeals.php>My meals</a> &nbsp;";
                echo "Welcome, $first $last! (<a href = login.php>$log</a>) &nbsp;";
            }
            else{
                echo "<a href = login.php>$log</a> &nbsp;";
            }
    echo'</div>'; 
?>