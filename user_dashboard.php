<?php
session_start();

// If not logged in, redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to DB
$conn = new mysqli("localhost", "root", "Pooja@2004", "pharmapulse_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user data
$userId = $_SESSION['user_id'];
$result = $conn->query("SELECT name, email, mobile, address FROM users WHERE id = $userId");

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_name'] = $row['name'];
    $_SESSION['user_email'] = $row['email'];
    $_SESSION['user_mobile'] = $row['mobile'];
    $_SESSION['user_address'] = $row['address'];
} else {
    echo "User not found.";
    exit();
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>PharmaPulse User Dashboard</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
        <style>
            body {
                margin: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(to right, #e0f7fa, #ffffff);
            }

            .container {
                display: flex;
            }

            .sidebar {
                width: 220px;
                height: 100vh;
                background: linear-gradient(to bottom, #00CED1, #004AAD);
                color: #fff;
                padding-top: 20px;
                position: fixed;
                left: 0;
                top: 0;
            }

            .sidebar h2 {
                text-align: center;
                margin-bottom: 30px;
                font-size: 24px;
            }

            .sidebar ul {
                list-style: none;
                padding: 0;
            }

            .sidebar ul li {
                padding: 15px 20px;
                cursor: pointer;
                transition: background 0.3s;
            }

            .sidebar ul li:hover {
                background-color: rgba(255, 255, 255, 0.1);
            }

            .sidebar ul li i {
                margin-right: 10px;
            }

            .main-content {
                margin-left: 220px;
                padding: 40px;
                flex: 1;
            }

            .main-content h1 {
                color: #007bff;
            }

            .dashboard-cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 20px;
                margin-top: 30px;
            }

            .card {
                background-color: #fff;
                padding: 25px;
                border-radius: 15px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: transform 0.2s;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            .card i {
                font-size: 32px;
                color: #007bff;
                margin-bottom: 10px;
            }

            .modal {
                display: none;
                position: fixed;
                top: 50%;
                left: 72%;
                transform: translate(-50%, -50%);
                width: 350px;
                background-color: #fff;
                border-radius: 12px;
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
                padding: 25px;
                z-index: 1001;
            }

            .modal.active {
                display: block;
            }

            .modal h3 {
                margin-bottom: 20px;
                color: #007bff;
                text-align: center;
            }

            .modal input,
            .modal textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 12px;
                border: 1px solid #ccc;
                border-radius: 8px;
            }

            .modal button {
                background-color: #007bff;
                color: #fff;
                border: none;
                padding: 10px 18px;
                border-radius: 6px;
                cursor: pointer;
                margin-left: 5px;
            }

            .modal .close-btn {
                position: absolute;
                right: 15px;
                top: 10px;
                font-size: 20px;
                cursor: pointer;
                color: #333;
            }

            #upload-img {
                display: none;
            }

            @media (max-width: 768px) {
                .container {
                    flex-direction: column;
                }

                .main-content {
                    margin-left: 0;
                }

                .sidebar {
                    position: relative;
                    width: 100%;
                    height: auto;
                }

                .modal {
                    left: 50%;
                }
            }
            /* Style for the view order modal */
            #viewOrderModal {
                left: 50%; /* Adjust for smaller screens if needed */
            }
            #viewOrderModal form {
                text-align: center;
            }
            #viewOrderModal input[type="text"] {
                margin-bottom: 15px;
            }
            #viewOrderModal button {
                margin-top: 10px;
            }
            .order-details {
                margin-top: 20px;
                border: 1px solid #ccc;
                padding: 15px;
                border-radius: 8px;
                background-color: #f9f9f9;
            }
            .order-details h4 {
                color: #007bff;
                margin-top: 0;
            }
            .order-details p {
                margin-bottom: 8px;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="sidebar">
                <h2>PharmaPulse</h2>
                <ul>
                    <li><i class="fas fa-home"></i> Dashboard</li>
                    <li onclick="showModal('profileModal')"><i class="fas fa-user-cog"></i>Profile</li>
                    <li onclick="window.location.href='medicine_table.php'"><i class="fas fa-pills"></i> View Medicines</li>
                    <li onclick="showModal('orderModal')"><i class="fas fa-shopping-cart"></i> Order Medicine</li>
                    <!-- <li onclick="showModal('viewOrderModal')"><i class="fas fa-search"></i> View Order</li> -->
                    <li onclick="window.location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> Logout</li>
                </ul>
            </div>

            <div class="main-content">
                <h1>Welcome,
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
                <div class="dashboard-cards">
                    <div class="card" onclick="window.location.href='medicine_table.php'">
                        <i class="fas fa-tablets"></i>
                        <h3>View Medicines</h3>
                        <p>Check available medicine details</p>
                    </div>

                    <div class="card" onclick="showModal('orderModal')">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Order Medicine</h3>
                        <p>Place new medicine orders</p>
                    </div>
                    <div class="card" onclick="showModal('profileModal')">
                        <i class="fas fa-user-edit"></i>
                        <h3>Update Profile</h3>
                        <p>Modify your personal info</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="profileModal">
            <span class="close-btn" onclick="hideModal()">&times;</span>
            <h3>Profile</h3>
            <!-- <label for="upload-img">
                <img src="https://via.placeholder.com/80" alt="Profile Picture" id="preview-img"
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #007bff;margin:auto;display:block;">
            </label> -->
            <input type="file" id="upload-img" accept="image/*" onchange="loadImage(event)" />
            <form onsubmit="saveProfile(event)">
                <input type="text" id="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($_SESSION['user_name']); ?>" />
                <input type="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" />
                <input type="text" id="phone" placeholder="Phone Number" value="<?php echo htmlspecialchars($_SESSION['user_mobile']); ?>" />
                <textarea id="address" rows="3" placeholder="Address"><?php echo htmlspecialchars($_SESSION['user_address']); ?></textarea>
                <div style="text-align:right;margin-top:10px;">
                    <!-- <button type="button" onclick="updateProfile()">Update</button> -->
                    <button type="submit">Save</button>
                </div>
            </form>
        </div>

        <div class="modal" id="orderModal">
            <span class="close-btn" onclick="hideModal()">&times;</span>
            <h3>Order Medicine</h3>
            <form action="order_medicine.php" method="POST">
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="text" name="phone_number" placeholder="Phone Number" required>
                <textarea name="address" placeholder="Address" rows="3" required></textarea>
                <input type="text" name="medicine_name" placeholder="Medicine Name" required>
                <input type="number" name="quantity" placeholder="Quantity" min="1" required>
                <input type="text" id="orderDate" readonly>
                <div style="text-align:right;margin-top:10px;">
                    <button type="submit">Request</button>
                </div>
            </form>
        </div>

        <div class="modal" id="viewOrderModal">
            <span class="close-btn" onclick="hideModal()">&times;</span>
            <h3>View Order</h3>
            <form id="viewOrderForm" onsubmit="fetchOrderDetails(event)">
                <input type="text" id="orderName" placeholder="Enter your name" required>
                <button type="submit">See Order</button>
            </form>
            <div id="orderDetails" class="order-details" style="display:none;">
                <h4>Order Details</h4>
                <p><strong>Medicine Name:</strong> <span id="orderMedicineName"></span></p>
                <p><strong>Quantity:</strong> <span id="orderQuantity"></span></p>
                <p><strong>Order Date:</strong> <span id="orderDateDetails"></span></p>
                <p><strong>Status:</strong> <span id="orderStatus"></span></p>
                </div>
        </div>

        <script>
            function showModal(modalId) {
                document.getElementById(modalId).classList.add("active");
            }

            function hideModal() {
                document.querySelectorAll('.modal').forEach(modal => modal.classList.remove("active"));
                // Optionally clear content of view order modal on close
                if (document.getElementById('viewOrderModal').classList.contains('active')) {
                    document.getElementById('orderDetails').style.display = 'none';
                    document.getElementById('orderMedicineName').textContent = '';
                    document.getElementById('orderQuantity').textContent = '';
                    document.getElementById('orderDateDetails').textContent = '';
                    document.getElementById('orderStatus').textContent = '';
                    document.getElementById('viewOrderForm').reset();
                }
            }

            function loadImage(event) {
                const output = document.getElementById('preview-img');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = () => URL.revokeObjectURL(output.src);
            }

            function saveProfile(e) {
                e.preventDefault();
                alert("Profile saved successfully!");
            }

            function updateProfile() {
                const name = document.getElementById('fullname').value;
                const email = document.getElementById('email').value;
                const mobile = document.getElementById('phone').value;
                const address = document.getElementById('address').value;

                // Log data to check
                console.log({
                    name,
                    email,
                    mobile,
                    address
                });

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "update_profile.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    const response = JSON.parse(this.responseText);
                    if (response.status === "success") {
                        alert("Profile updated successfully!");
                        location.reload(); // reload to reflect changes
                    } else {
                        alert("Update failed: " + response.message);
                    }
                };
                console.log("AJAX sent");
                xhr.send(`name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&mobile=${encodeURIComponent(mobile)}&address=${encodeURIComponent(address)}`);
            }

            function requestOrder(e) {
                e.preventDefault();
                alert("Medicine order request submitted!");
            }

            document.addEventListener('DOMContentLoaded', () => {
                const dateInput = document.getElementById('orderDate');
                if (dateInput) {
                    const today = new Date();
                    const formatted = today.toISOString().split('T')[0]; // "YYYY-MM-DD"
                    dateInput.value = `Order Date: ${formatted}`;
                }
            });

            function fetchOrderDetails(event) {
                event.preventDefault();
                const orderName = document.getElementById('orderName').value;
                const orderDetailsDiv = document.getElementById('orderDetails');
                const orderMedicineNameSpan = document.getElementById('orderMedicineName');
                const orderQuantitySpan = document.getElementById('orderQuantity');
                const orderDateDetailsSpan = document.getElementById('orderDateDetails');
                const orderStatusSpan = document.getElementById('orderStatus');

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "get_order_details.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (this.status === 200) {
                        const response = JSON.parse(this.responseText);
                        if (response.status === "success" && response.order) {
                            orderMedicineNameSpan.textContent = response.order.medicine_name;
                            orderQuantitySpan.textContent = response.order.quantity;
                            orderDateDetailsSpan.textContent = response.order.order_date;
                            orderStatusSpan.textContent = response.order.status;
                            orderDetailsDiv.style.display = 'block';
                        } else {
                            alert(response.message || "No orders found for this name.");
                            orderDetailsDiv.style.display = 'none';
                        }
                    } else {
                        alert("Error fetching order details.");
                        orderDetailsDiv.style.display = 'none';
                    }
                };
                xhr.onerror = function() {
                    alert("Network error occurred while fetching order details.");
                    orderDetailsDiv.style.display = 'none';
                };
                xhr.send(`name=${encodeURIComponent(orderName)}`);
            }
        </script>
    </body>

    </html>