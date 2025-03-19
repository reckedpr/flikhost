<?php
//Yea segregation is better for this type of handling so no more api.php


header('Content-Type: application/json'); // Return JSON always

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Read JSON input
    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    // Validate input
    if (!$data || empty($data['forename']) || empty($data['surname']) || empty($data['email']) || empty($data['password'])) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        exit();
    }

    $forename = htmlspecialchars(trim($data['forename']));
    $surname = htmlspecialchars(trim($data['surname']));
    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $password = password_hash(trim($data['password']), PASSWORD_DEFAULT);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[^@]+@[^@]+\.[^@]+$/', $email)) {
        echo json_encode(["success" => false, "message" => "Invalid email format"]);
        exit();
    }


    try {
        require_once '../db/dbh.inc.php'; // Database connection

        $query = "INSERT INTO users (fname, sname, email, pwd) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$forename, $surname, $email, $password]);

        // Close connections
        $pdo = null;
        $stmt = null;

        // Return JSON success response
        echo json_encode(["success" => true, "message" => "User created successfully!", "email" => $email]);
        exit();

    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit();
}

