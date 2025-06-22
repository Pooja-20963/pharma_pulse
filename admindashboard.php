<?php
$conn = new mysqli("localhost", "root", "Pooja@2004", "pharmapulse_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_medicine'])) {
    $medicine_id = $_POST['medicine_id'];

    // Sanitize input to prevent SQL injection
    $medicine_id = mysqli_real_escape_string($conn, $medicine_id);

    $sql = "DELETE FROM medicines WHERE id = '$medicine_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Medicine deleted successfully'); window.location.href='admindashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting medicine: " . $conn->error . "'); window.location.href='admindashboard.php';</script>";
    }

    $conn->close();
    exit(); // Stop further execution after the delete attempt
}

// The rest of your HTML code for the admin dashboard
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mr. Pharmacist - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        /* ... (Your existing CSS styles) ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }
        
        body {
            display: flex;
            height: 100vh;
            background-color: #f4f9ff;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(to bottom, #00CED1, #004AAD);
            color: white;
            padding-top: 30px;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .sidebar ul {
            list-style: none;
        }
        
        .sidebar ul li {
            padding: 15px 25px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .sidebar ul li:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .sidebar ul li i {
            margin-right: 15px;
            font-size: 18px;
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f9fcff;
            overflow-y: auto;
            position: relative;
        }
        
        .section-title {
            font-size: 28px;
            font-weight: bold;
            color: #004AAD;
            margin-bottom: 20px;
        }
        
        .dashboard-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .widget {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            flex: 1;
            min-width: 220px;
            color: #004AAD;
            transition: 0.3s ease;
        }
        
        .widget:hover {
            transform: translateY(-5px);
        }
        
        .widget h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .widget p {
            font-size: 26px;
            font-weight: bold;
        }
        
        .messages-box {
            margin-top: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 25px;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .messages-box h3 {
            margin-bottom: 15px;
            font-size: 20px;
            color: #004AAD;
        }
        
        .message {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        
        .message:last-child {
            border-bottom: none;
        }
        
        .message strong {
            color: #00CED1;
        }
        
        .message p {
            font-size: 14px;
            color: #333;
            margin-top: 5px;
        }
        
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 60%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            border-radius: 10px;
            width: 400px;
        }
        
        .modal h3 {
            margin-bottom: 20px;
            color: #004AAD;
            text-align: center;
        }
        
        .modal input,
        .modal textarea,
        .modal select {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        
        .modal button {
            background-color: #004AAD;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
        }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            z-index: 999;
        }
        
        .close-btn {
            float: right;
            cursor: pointer;
            font-size: 20px;
            color: red;
        }
        
        select.styled-select {
            appearance: none;
            background-color: #fff;
            font-size: 14px;
            color: #333;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar ul li span {
                display: none;
            }
            .main-content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>PharmaPulse</h2>
        <ul>
            <li><i class="fas fa-chart-line"></i> Dashboard</li>
            <li onclick="showModal('profileModal')"><i class="fas fa-user"></i> Profile</li>
            <li onclick="showModal('addModal')"><i class="fas fa-plus-circle"></i> Add Medicine</li>
            <li onclick="showModal('updateModal')"><i class="fas fa-edit"></i> Update Medicine</li>
            <li onclick="showModal('deleteModal')"><i class="fas fa-trash-alt"></i> Delete Medicine</li>
            <li onclick="window.location.href='view_medicine.php'"><i class="fas fa-box"></i> Recent Orders</li>
            <li onclick="window.location.href='logout.php'"><i class="fas fa-sign-out-alt"></i> Sign Out</li>
        </ul>
    </div>

    <div class="main-content">
        <div class="section-title">Dashboard</div>

        <div class="dashboard-grid">
            <div class="widget">
                <h3>Total Medicines</h3>
                <p>154</p>
            </div>
            <div class="widget">
                <h3>Out of Stock</h3>
                <p>12</p>
            </div>
            <div class="widget">
                <h3>Expired</h3>
                <p>5</p>
            </div>
            <div class="widget">
                <h3>Sales Today</h3>
                <p>₹4,300</p>
            </div>
        </div>

        <!-- <div class="messages-box">
            <h3>Customer Feedback</h3>
            <div class="message"><strong>Ravi Kumar</strong>
                <p>"Excellent service! Got my medicine on time."</p>
            </div>
            <div class="message"><strong>Neha Sharma</strong>
                <p>"Please restock Paracetamol 650mg."</p>
            </div>
            <div class="message"><strong>Aditya Verma</strong>
                <p>"The dosage details are very helpful. Thanks!"</p>
            </div>
            <div class="message"><strong>Priya Joshi</strong>
                <p>"Add a chat support option please."</p>
            </div>
            <div class="message"><strong>Aman Tripathi</strong>
                <p>"Very user-friendly platform."</p>
            </div>
        </div> -->
    </div>

    <div class="overlay" id="overlay" onclick="hideModal()"></div>

    <div class="modal" id="addModal">
        <span class="close-btn" onclick="hideModal()">&times;</span>
        <h3>Add Medicine</h3>
        <form action="add_medicine.php" method="POST">
            <input type="number" name="id" placeholder="Medicine ID" required />
            <input type="text" name="drug_name" placeholder="Drug Name" required />
            <input type="text" name="drug_class" placeholder="Drug Class" required />
            <textarea name="side_effects" placeholder="Side Effects" rows="2" required></textarea>
            <textarea name="used_for" placeholder="Used For" rows="3" required></textarea>
            <select name="dosage_form" class="styled-select" required>
                <option value="" disabled selected>Select Dosage Form</option>
                <option value="Tablet">Tablet</option>
                <option value="Capsule">Capsule</option>
                <option value="Syrup">Syrup</option>
                <option value="Injection">Injection</option>
                <option value="Ointment">Ointment</option>
            </select>
            <input type="text" name="dose" placeholder="Dose (e.g., 500mg)" required />
            <input type="number" name="price" placeholder="Price (₹)" required />
            <button type="submit">Add</button>
        </form>
    </div>

    <div class="modal" id="updateModal">
        <span class="close-btn" onclick="hideModal()">&times;</span>
        <h3>Update Medicine</h3>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <input type="text" name="medicine_id" placeholder="Medicine ID" required />
            <input type="text" name="field" placeholder="Updated Field" required />
            <input type="text" name="new_value" placeholder="New Value" required />
            <button type="submit">Update</button>
        </form>
    </div>

    <div class="modal" id="deleteModal">
        <span class="close-btn" onclick="hideModal()">&times;</span>
        <h3>Delete Medicine</h3>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" onsubmit="return confirmDelete()">
            <input type="text" name="medicine_id" placeholder="Medicine ID" required />
            <button type="submit" style="background-color: red;" name="delete_medicine">Delete</button>
        </form>
    </div>
    <div class="modal" id="profileModal">
        <span class="close-btn" onclick="hideModal()">&times;</span>
        <h3>Profile</h3>
        <div class="profile-header" style="display:flex;align-items:center;gap:20px;margin-bottom:20px;">
            <!-- <label for="upload-img">
                <img src="https://via.placeholder.com/80" alt="Profile Picture" id="preview-img"
                    style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #007bff;cursor:pointer;" />
            </label> -->
            <input type="file" id="upload-img" accept="image/*" onchange="loadImage(event)" style="display:none;" />
            <div class="profile-info">
                <h4 id="profileName">Admin User</h4>
            </div>
        </div>
        <form onsubmit="saveProfile(event)">
            <input type="text" id="title" placeholder="Title (e.g., Mr., Dr.)" value="Administrator" />
            <input type="text" id="fullname" placeholder="Full Name" value="Admin John" />
            <input type="email" id="email" placeholder="Email" value="admin@pharmapulse.com" />
            <input type="password" id="password" placeholder="Password" value="admin123" />
            <input type="text" placeholder="Phone Number" value="98765 43210">
            <textarea id="address" rows="3" placeholder="Address">123 Naik Street, Mumbai</textarea>
            <div style="text-align:right;margin-top:10px;">
                <!-- <button type="button" onclick="updateProfile()">Update</button> -->
                <button type="submit">Save</button>
            </div>
        </form>
    </div>

    <script>
        function showModal(id) {
            document.getElementById(id).style.display = "block";
            document.getElementById("overlay").style.display = "block";
        }

        function hideModal() {
            document.querySelectorAll('.modal').forEach(modal => modal.style.display = "none");
            document.getElementById("overlay").style.display = "none";
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this medicine?");
        }

        function loadImage(event) {
            const output = document.getElementById('preview-img');
            output.src = URL.createObjectURL(event.target.files[0]);
        }

        function saveProfile(e) {
            e.preventDefault();
            const name = document.getElementById('fullname').value;
            document.getElementById('profileName').textContent = name;
            alert("Profile saved successfully!");
        }

        function updateProfile() {
            const name = document.getElementById('fullname').value;
            document.getElementById('profileName').textContent = name;
            alert("Profile updated!");
        }
    </script>
</body>

</html>