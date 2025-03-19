<?php
$data = json_decode(file_get_contents("php://input"), true);
header("Content-Type: application/json");

function guidv4($data = null) {
    $data = $data ?? random_bytes(16);
    if (strlen($data) !== 16) {
        throw new Exception("Invalid data length for UUID generation");
    }

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function get_base64_mime_type($binary_data) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    return $finfo->buffer($binary_data);
}

if (!isset($data['image'], $data['imagename'], $data['user_id'], $data['username'])) {
    http_response_code(400);
    exit(json_encode(["success" => false, "message" => "Missing required fields."]));
}

if (!filter_var($data['user_id'], FILTER_VALIDATE_INT)) {
    http_response_code(400);
    exit(json_encode(["success" => false, "message" => "Invalid user ID format."]));
}

$base64_string = $data['image'];

if (!preg_match('/^data:image\/(jpeg|png|gif);base64,/', $base64_string)) {
    http_response_code(400);
    exit(json_encode(["success" => false, "message" => "Malformed base64 input."]));
}

$image_data = explode(',', $base64_string);
$decoded_image = base64_decode(end($image_data), true);

if ($decoded_image === false) {
    http_response_code(400);
    exit(json_encode(["success" => false, "message" => "Invalid base64 data."]));
}

$mime_type = get_base64_mime_type($decoded_image);

$allowed_mime_types = ["image/jpeg" => ".jpg", "image/png" => ".png", "image/gif" => ".gif"];
if (!isset($allowed_mime_types[$mime_type])) {
    http_response_code(400);
    exit(json_encode(["success" => false, "message" => "Unsupported image format."]));
}

$imageUUID = guidv4();
$image_name = trim(preg_replace('/[^a-zA-Z0-9-_]/', '_', $data['imagename']), '_');

if (empty($image_name)) {
    http_response_code(400);
    exit(json_encode(["success" => false, "message" => "Invalid image name."]));
}
$real_image_name = $image_name;
$image_name = "{$image_name}_{$imageUUID}";
$image_path = "../../storedImages/{$image_name}" . $allowed_mime_types[$mime_type];

if (!file_exists("../../storedImages/") && !mkdir("../../storedImages/", 0775, true)) {
    http_response_code(500);
    exit(json_encode(["success" => false, "message" => "Failed to create image directory."]));
}

if (!file_put_contents($image_path, $decoded_image)) {
    http_response_code(500);
    exit(json_encode(["success" => false, "message" => "Failed to save image."]));
}

require_once '../db/dbh.inc.php';

$query = "INSERT INTO images (id, user_id, username, image_path, image_name) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($query);
$stmt->execute([$imageUUID, $data['user_id'], $data['username'], $image_path, $real_image_name]);

echo json_encode([
    "success" => true,
    "message" => "Image saved successfully!",
    "path" => $image_name,
    "image_id" => $imageUUID,
    "image_type" => $mime_type,
    "image_name" => $real_image_name
]);
?>
