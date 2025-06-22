<?php
$conn = new mysqli("localhost", "root", "Pooja@2004", "pharmapulse_db");
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $stmt = $conn->prepare("SELECT id, medicine_name, quantity, order_date, status FROM orders WHERE full_name = ? ORDER BY order_date DESC");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $orders = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode(['status' => 'success', 'orders' => $orders]);
    } else {
        echo json_encode(['status' => 'success', 'orders' => []]); // No orders found
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Name parameter is missing.']);
}

$conn->close();
?>