<?php
$conn = new mysqli("localhost", "root", "Pooja@2004", "pharmapulse_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Accept/Deny actions
if (isset($_POST['order_id']) && isset($_POST['action'])) {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];
    $new_status = ($action === 'accept') ? 'Accepted' : 'Denied';

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page and see the updated status
    header("Location: view_medicine.php"); // Or admin_orders.php if you rename
    exit();
}

// Fetch all orders
$sql = "SELECT * FROM orders ORDER BY order_date DESC"; // Order by date, newest first
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - View Order Requests</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        /* Header Styling */
        header {
            background: linear-gradient(to right, #00FFFF, #42878f, #4169E1);
            padding: 20px;
            text-align: center;
            color: white;
            font-size: 26px;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }

        /* Search Bar Styling */
        .search-container {
            text-align: center;
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }

        .search-container input {
            width: 50%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #4169E1;
            border-radius: 5px;
            outline: none;
            transition: 0.3s ease;
        }

        .search-container input:focus {
            border-color: #00bfff;
            box-shadow: 0 0 5px rgba(0, 191, 255, 0.5);
        }

        /* Table Container */
        .table-container {
            width: 95%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #4169E1;
            color: white;
            font-size: 17px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e6f2ff;
            cursor: pointer;
        }

        /* Status Styling */
        .status {
            font-weight: bold;
        }

        .status-pending {
            color: orange;
        }

        .status-accepted {
            color: green;
        }

        .status-denied {
            color: red;
        }

        /* Action Buttons */
        .action-buttons button {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .accept-btn {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        .deny-btn {
            background-color: #f44336; /* Red */
            color: white;
        }
    </style>
</head>

<body>

    <header>View Medicine Requests</header>

    <div class="search-container">
        <input type="text" id="searchBox" onkeyup="searchMedicine()" placeholder="Search for requests...">
    </div>

    <div class="table-container">
        <table id="medicineTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Mobile</th>
                    <th>Address</th>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['phone_number']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= $row['order_date'] ?></td>
                            <td class="status status-<?= strtolower($row['status']) ?>"><?= htmlspecialchars($row['status']) ?></td>
                            <td class="action-buttons">
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="accept-btn">Accept</button>
                                    </form>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="deny">
                                        <button type="submit" class="deny-btn">Deny</button>
                                    </form>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No order requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchMedicine() {
            const input = document.getElementById("searchBox").value.toUpperCase();
            const table = document.getElementById("medicineTable");
            const tr = table.getElementsByTagName("tr");

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName("td");
                let match = false;

                for (let j = 0; j < td.length; j++) {
                    if (td[j] && td[j].textContent.toUpperCase().includes(input)) {
                        match = true;
                        break;
                    }
                }

                tr[i].style.display = match ? "" : "none";
            }
        }
    </script>

</body>

</html>

<?php $conn->close(); ?>