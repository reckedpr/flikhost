<?php
session_start();


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "You must be logged in to like an image."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $image_id = $_POST["image_id"];
    $user_id = $_SESSION["user_id"]; // Get logged-in user's ID

    try {
        require_once '../db/dbh.inc.php';

        // Check if the user already liked this image
        $stmt = $pdo->prepare("SELECT id FROM likes WHERE user_id = ? AND image_id = ?");
        $stmt->execute([$user_id, $image_id]);

        $existing_like = $stmt->fetch();

        if ($existing_like) {
            // Unlike the image (if already liked)
            $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND image_id = ?");
            $stmt->execute([$user_id, $image_id]);
            echo json_encode(["success" => true, "liked" => false, "message" => "Like removed"]);
        } else {
            // Insert new like
            $stmt = $pdo->prepare("INSERT INTO likes (user_id, image_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $image_id]);
            echo json_encode(["success" => true, "liked" => true, "message" => "Image liked"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
