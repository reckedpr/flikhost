<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<?php
    if(isset($_GET["login"])){
        if($_GET["login"] == "true" ){
            // noti logged in succy
        }
    }elseif(isset($_GET["session_expired"])){
        if($_GET["session_expired"] == true || $_GET["session_expired"] == "true"){
            //noti session exired 
        }
    }

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account - Flikhost</title>

    <link rel="stylesheet" href="../css/pages/home.css">
    <link rel="shortcut icon" href="../assets/favicon.png" type="image/x-icon">

</head>
<body>
    <div class="centered">
        <div class="mainBox">
            <div class="header">
                <?php 
                if(isset($_SESSION['user_name'])) {
                    echo "<h2>Welcome back, {$_SESSION['user_name']}</h2>";
                } else {
                    echo "<h2>Not logged in :(</h2>";
                }
                ?>
                
                <div class="accountAction">

                    <?php
                    if(isset($_SESSION['user_id']) ) {
                        echo "<button onclick='signout()'>sign out</button>";
                    } elseif(!isset($_GET["signout"])) {
                        echo "<button class='accountActionGuest' onclick='signup()'>sign in</button>";
                    } elseif(isset($_GET["signout"])) {
                        if($_GET["signout"] == "true"){
                            echo "<button class='accountActionGuest' onclick='signup()'>sign in</button>";
                        }
                    }?>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function signout(){
        window.location.href = "../includes/auth/logout.inc.php";
    }

    function signup(){
        window.location.href = "login";
    }
</script>
</html>