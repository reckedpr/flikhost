<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Read JSON input
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (!isset($data["delete"])) {
        echo json_encode(["success" => false, "message" => "Invalid request."]);
        exit();
    }

    if ($data["delete"] == "user") {
        if (!isset($data["id"])) {
            echo json_encode(["success" => false, "message" => "User ID missing."]);
            exit();
        }

        try {
            require_once 'dbh.inc.php'; // Database connection
            $query = "DELETE FROM users WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([(int)$data["id"]]);

            echo json_encode(["success" => true, "message" => "User deleted successfully!"]);
            exit();
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
            exit();
        }
    } elseif ($data["delete"] == "image") {
        if (!isset($data["image_id"])) {
            echo json_encode(["success" => false, "message" => "Image ID missing."]);
            exit();
        }

        try {
            require_once '../db/dbh.inc.php'; // Database connection

            if(isset($_POST["allimage"])){
                if($_POST["allimage"] == true){

                    $query = "SELECT image_path FROM images";
                    $stmt = $pdo->prepare($query);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    //Will create later more, pressing matters rn.

                }
            }

            // Fetch image path
            $query = "SELECT image_path FROM images WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$data["image_id"]]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                echo json_encode(["success" => false, "message" => "Image not found."]);
                exit();
            }

            $image_path = $result["image_path"];

            // Delete file from server
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            // Delete record from database
            $query = "DELETE FROM images WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$data["image_id"]]);

            echo json_encode(["success" => true, "message" => "Image deleted successfully."]);
            exit();
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
            exit();
        }
    }
}
// pelican type beeeeeeeeeeeeat