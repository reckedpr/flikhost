<?php
session_start();


//MMMMMMM this is so easy how tf did i not think of this

function check_image($imagePath){
    if(file_exists($imagePath.".png")){
        $type = ".png";
    }elseif(file_exists($imagePath.".jpg")||file_exists($imagePath.".jpeg")||file_exists($imagePath.".jfif")){
        $type = ".jpg";
    }elseif(file_exists($imagePath.".gif")) {//Can just assume gif because last supported file type and if it isnt then boowho
        $type = ".gif";
    }else{
        $type = null;
    }

    return $type;
}



echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your image</title>
</head>';

if(isset($_GET["image"])){
    require_once '../includes/db/dbh.inc.php';
    $imageEndsWith = check_image("../storedImages/".$_GET["image"]);
    if($imageEndsWith != null){

        //$query = "SELECT user_id, username, image_name, id FROM images";
        $query = "SELECT * FROM images WHERE image_path = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute(["../../storedImages/".$_GET["image"].$imageEndsWith]);
        $result = $stmt->fetchAll();
        
        $username = $result[0]["username"];
        $file_name = $result[0]["image_name"];
        $image_id = $result[0]["id"];
        $user_id = $result[0]["user_id"];

        $sql = "SELECT COUNT(*) FROM likes WHERE image_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$image_id]);

        $like_count = $stmt->fetchColumn();
?>
<body>
    <div style="justify-content: center;display:flex;align-items: center;background-color:aqua"><h1>File name:&nbsp;<?php echo $file_name; ?></h1></div>
    <div style="background-color:black;"><img style="display: block;margin: auto;max-width=720px;max-height: 1200px" src="../storedImages/<?php echo $_GET["image"] . $imageEndsWith;?>"/></div>
    <div style="display:flex;background-color:aqua">
        <h3>Uploaded by:&nbsp;</h3>
        <p style="display:flex;align-items: center"><?php echo $username ?></p>
        <h3 style="margin-left: auto;">Likes: <?php echo $like_count;?></h3>
    </div>
    <button id="likeButton" data-image-id="<?= $image_id ?>">I like this post!</button>
    <script>
document.getElementById("likeButton").addEventListener("click", function() {
    let imageId = this.getAttribute("data-image-id");

    fetch("/includes/utils/like.inc.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "image_id=" + encodeURIComponent(imageId)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message); // Show success message
            location.reload(); // Reload page to update like count
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
});
</script>
</body>
</html>

<?php 
}else{
    Header("Location: 404");
}}else{
    Header("Location: 404");
}
?>