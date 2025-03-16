<?php

$uri = "mysql://avnadmin:AVNS_RnOp02y4gwxDzOiJ4m8@flikhost-db-flikhost.k.aivencloud.com:13190/images?ssl-mode=REQUIRED";

$fields = parse_url($uri);

// build the DSN including SSL settings
$conn = "mysql:";
$conn .= "host=" . $fields["host"];
$conn .= ";port=" . $fields["port"];;
$conn .= ";dbname=login_details";
$conn .= ";sslmode=verify-ca;sslrootcert=ca.pem";

try {
  $pdo = new PDO($conn, $fields["user"], $fields["pass"]);
  
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}