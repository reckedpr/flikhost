<?php
session_start();
header('Content-Type: application/json'); // Return JSON response

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once 'dbh.inc.php'; // Database connection

    $jsonData = file_get_contents("php://input");
    $data = json_decode($jsonData, true);

    if (!$data || empty($data['email']) || empty($data['password'])) {
        echo json_encode(["success" => false, "message" => "Missing email or password"]);
        exit();
    }

    $email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($data['password']);

    try {
        // Check if email exists
        $query = "SELECT id, fname, sname, pwd FROM users WHERE email = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['pwd'])) {
            // Authentication successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fname'] . $user['sname'][0];

            echo json_encode(["success" => true, "message" => "Login successful!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid credentials"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}