<?php
//Yea segregation is better for this type of handling so no more api.php


header('Content-Type: application/json'); // Return JSON always

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Read JSON input
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    $id = htmlspecialchars(trim($data['id']));


    try {
        require_once 'dbuser.inc.php'; // Database connection

        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([(int)$id]);

        // Close connections
        $pdo = null;
        $stmt = null;

        // Return JSON success response
        echo json_encode(["success" => true, "message" => "User deleted successfully!"]);
        exit();

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

