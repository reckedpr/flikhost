<?php
session_start(); //Used to check if signed in is all
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flikhost</title>
</head>
<body>
    <!--Basic overview of how this page should look-->
    <h1 style="display:flex; justify-content: center;">Welcome to flikhost</h1>
    <h3>What is Flikhost?</h3>
    <p>
        Flikhost is a free image hosting service that doesn't cost you a penny, you don't even need an account to upload images!<br>
        <img style="display:block; margin: auto;width: 300px; height: 300px" src="assets/silly/cool.png"/>
    </p>
    <p>click <a href="<?php
    if(isset($_SESSION["user_session_id"])){
        echo "../upload";
    }else{
        echo "../signup";
    }
    ?>">HERE</a> to get started.</p>
</body>
</html>