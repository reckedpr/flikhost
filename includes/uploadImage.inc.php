<?php
// Read raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

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
$imageUUID = guidv4();


if (isset($data['image']) && isset($data['imagename'])) {
    $base64_string = $data['image']; // Base64 image data
    $image_name = preg_replace('/[^a-zA-Z0-9-_]/', '_', $data['imagename']); // Sanitize filename

    $image_path = "../images/{$image_name}.png"; // Set file path

    // Decode the base64 string
    $image_data = explode(',', $base64_string);
    $decoded_image = base64_decode(end($image_data)); // Handle "data:image/png;base64,..." prefix

    if ($decoded_image === false) {
        die(json_encode(["success" => false, "message" => "Invalid base64 data."]));
    }

    // Ensure the images directory exists
    if (!file_exists("../images/")) {
        mkdir("../images/", 0775, true); // Create directory if missing
    }

    // Save the image
    if (file_put_contents($image_path, $decoded_image)) {
        require_once 'dbimages.inc.php'; // Connect to the images database

        //UHHHHH IM SO TIRED (04:14)
        // TODO: Add pointer to the image in file system and link it to the user in the database


        $query = "INSERT INTO images (user_id, username, image_path, image_id) VALUES (?, ?, ?, ?)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([$data['user_id'], $data['username'], $image_path, $imageUUID]);

        echo json_encode(["success" => true, "message" => "Image saved successfully!", "path" => $image_path, "image_id" => $imageUUID]);

    } else {
        echo json_encode(["success" => false, "message" => "Failed to save image."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No image data provided."]);
}
?>
