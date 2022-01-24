<!DOCTYPE html>
<html>
    <head>
        <title> User-specific session information </title>
    </head>
    <body>
        
        <?php
            session_start();
            print_r($_SESSION);
            echo "<br/>";
            
            if(isset($_SESSION["userid"])){
                echo "user info in database: <br/>";
                $userid = $_SESSION["userid"];
                $DB_HOST='localhost';
                $DB_USER='fetcher1';
                $DB_PASS='1234';
                $DB_NAME='main';
                $db = pg_connect("host={$DB_HOST} user={$DB_USER} password={$DB_PASS} dbname={$DB_NAME}");
                
                $query = "SELECT * FROM customers WHERE userid='$userid'";
                $res = pg_query($db, $query);
                $row = pg_fetch_assoc($res);
                print_r($row);
            }
        ?>
        
    </body>
</html>