<?php
$host = "localhost";
$user = "root";
$password = "Pooja@2004";
$database = "pharmapulse_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $medicine_name = $_POST['medicine_name'];
    $quantity = (int) $_POST['quantity'];  // cast to integer for safety

    // Use prepared statements
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone_number, address, medicine_name, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $full_name, $phone_number, $address, $medicine_name, $quantity);

    if ($stmt->execute()) {
        echo "<script>alert('Medicine order request submitted successfully!'); window.location.href='user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error submitting order: " . $stmt->error . "'); window.location.href='user_dashboard.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
