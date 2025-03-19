<?php

$uri = "mysql://avnadmin:AVNS_RnOp02y4gwxDzOiJ4m8@flikhost-db-flikhost.k.aivencloud.com:13190/images?ssl-mode=REQUIRED";

$fields = parse_url($uri);

// build the DSN including SSL settings
$conn = "mysql:";
$conn .= "host=" . $fields["host"];
$conn .= ";port=" . $fields["port"];
$conn .= ";dbname=login_details";
$conn .= ";sslmode=verify-ca;sslrootcert=ca.pem";

try {
  $pdo = new PDO($conn, $fields["user"], $fields["pass"]);

  //$query = ""; //SQL statement to run on the database
  //$stmt = $pdo->prepare($query); // Prepares the statement
  //$stmt->execute(); //Executes the statement with, you can pass variables through an array 
  //$result = $stmt->fetchAll(); // Fetches all the results
  //echo json_encode($result); // Encodes the results in JSON format
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}