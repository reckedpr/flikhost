<?php
// Read raw JSON input
$data = json_decode(file_get_contents("php://input"), true);

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
        echo json_encode(["success" => true, "message" => "Image saved successfully!", "path" => $image_path]);

        require_once 'dbimages.inc.php'; // Connect to the images database

        //UHHHHH IM SO TIRED (04:14)
        // TODO: Add pointer to the image in file system and link it to the user in the database

    } else {
        echo json_encode(["success" => false, "message" => "Failed to save image."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No image data provided."]);
}
?>
