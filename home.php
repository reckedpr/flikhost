<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
<script>
    function signout(){
        window.location.href = "includes/logout.inc.php";
    }

    function signup(){
        window.location.href = "login.php";
    }
</script>
</html>
<?php
session_start(); //If they are logged in then all the asscoiated session variables are set

    if(isset($_GET["login"])){
        if($_GET["login"] == "true"){
            echo "<p>You are logged in!</p>";
            echo "<p>Your username is: ", $_SESSION['user_name'] , "</p>";
            echo "<p>Your id is: ", $_SESSION['user_id'] , "</p>"; 
            echo "<br><br><button onclick='signout()'>sign out</button>";
        }
    }
    if(isset($_GET["signout"])){
        if($_GET["signout"] == "true"){
            echo "<p>You have signed out!</p>";
            echo "<br><br><button onclick='signup()'>sign in</button>";
        }
    }

?>