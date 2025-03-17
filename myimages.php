<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>images</title>
</head>
<body>
<h1 style="justify-content:center;display:flex">Your images</h1>    
</body>
</html>
<?php

if(!isset($_SESSION["user_id"])) {
    echo "You are not logged in!<br>go to <a href='login'>login</a> page";
    exit;
}else{
    try {
        require_once 'includes/dbimages.inc.php';
        $stmt = $pdo->prepare("SELECT * FROM images WHERE user_id = ?");
        $stmt->execute([$_SESSION["user_id"]]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "<table>
        <tr>
            <th>Image</th>
            <th>Uploaded at</th>
            <th>Action</th>
        </tr>";
        foreach ($rows as $row) {
            echo "<tr>
            <td><img style='max-height: 500px; max-width: 500px;' src='{$row['image_path']}' alt='image'></td>
            <td>{$row['uploaded_at']}</td>
            <td><button class='delete-btn' onclick='removeImage({$row['image_id']})'>Delete</button></td>
        </tr>";
        }

    } catch (Exception $e) {
        echo "Query Error: " . $e->getMessage();
        exit;
    }
}
?>