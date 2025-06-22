<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit();
}

$conn = new mysqli("localhost", "root", "Pooja@2004", "pharmapulse_db");
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed"]));
}

// Get user input
$userId = $_SESSION['user_id'];
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$mobile = $conn->real_escape_string($_POST['mobile']);
$address = $conn->real_escape_string($_POST['address']);

// Prepare SQL query
$sql = "UPDATE users SET name=?, email=?, mobile=?, address=? WHERE id=?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(["status" => "error", "message" => "Prepared statement failed"]));
}

// Bind parameters
$stmt->bind_param("ssssi", $name, $email, $mobile, $address, $userId);

// Execute query
if ($stmt->execute()) {
    // Update session data if successful
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_mobile'] = $mobile;
    $_SESSION['user_address'] = $address;

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Update failed"]);
}

// Close statement
$stmt->close();
$conn->close();
?>
