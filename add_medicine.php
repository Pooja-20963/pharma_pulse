<?php
// Database connection details
$host = "localhost";
$user = "root";
$password = "Pooja@2004";
$database = "pharmapulse_db";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from POST request
$id = $_POST['id'];
$drug_name = $_POST['drug_name'];
$drug_class = $_POST['drug_class'];
$side_effects = $_POST['side_effects'];
$used_for = $_POST['used_for'];
$dosage_form = $_POST['dosage_form'];
$dose = $_POST['dose'];
$price = $_POST['price'];

// Prepare SQL query to insert data
$sql = "INSERT INTO medicines (id, drug_name, drug_class, side_effects, used_for, dosage_form, dose, price)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssssd", $id, $drug_name, $drug_class, $side_effects, $used_for, $dosage_form, $dose, $price);

// Execute the query
if ($stmt->execute()) {
    echo "<script>alert('Medicine added successfully!'); window.location.href = 'admindashboard.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
