<?php
$conn = new mysqli("localhost", "root", "Pooja@2004", "pharmapulse_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM medicines";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Medicines</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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

        /* Responsive Styling */
        @media (max-width: 768px) {
            .search-container input {
                width: 80%;
            }

            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <header>View Medicines</header>

    <!-- Search Bar -->
    <div class="search-container">
        <input type="text" id="searchBox" onkeyup="searchMedicine()" placeholder="Search for medicines...">
    </div>

    <!-- Medicine Table -->
    <div class="table-container">
        <table id="medicineTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Drug Name</th>
                    <th>Drug Class (Category)</th>
                    <th>Side Effects</th>
                    <th>Used For (Purpose)</th>
                    <th>Dosage Form</th>
                    <th>Dose</th>
                    <th>Price (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['drug_name']) ?></td>
                            <td><?= htmlspecialchars($row['drug_class']) ?></td>
                            <td><?= htmlspecialchars($row['side_effects']) ?></td>
                            <td><?= htmlspecialchars($row['used_for']) ?></td>
                            <td><?= htmlspecialchars($row['dosage_form']) ?></td>
                            <td><?= htmlspecialchars($row['dose']) ?></td>
                            <td><?= number_format($row['price'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No medicines found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- JavaScript for Search -->
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
