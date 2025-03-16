<?php
session_start();
header('Content-Type: application/json'); // Return JSON response

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


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
            $_SESSION["user_session_id"] = guidv4();

            echo json_encode(["success" => true, "message" => "Login successful!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid credentials"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}