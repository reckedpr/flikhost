<?php
session_start();
session_unset();
session_destroy();

if(isset($_GET["session_expired"]) == true || $_GET["session_expired"] == "true"){
    header("Location: /home?session_expired=true");
}else{
    header("Location: /home?signout=true");
}

exit();
?>
