<?php
// PHP Section: Handle Registration

// Connect to the database
$servername = "localhost";
$username = "root"; // your MySQL username
$password = "Pooja@2004"; // your MySQL password
$dbname = "pharmapulse_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// When form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Check if email already exists
    $check_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_sql);

    // If email already exists, check if passwords match
    if ($result->num_rows > 0) {
        // If passwords don't match, show error
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
            exit();
        } else {
            // Email exists and passwords match (which should not happen in this case)
            echo "<script>alert('Email already registered. Please login.'); window.location.href='login.php';</script>";
            exit();
        }
    }

    // If email doesn't exist and passwords match, proceed with registration
    if ($password === $confirm_password) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into users table
        $sql = "INSERT INTO users (name, email, user_password, mobile, address)
                VALUES ('$name', '$email', '$hashed_password', '$mobile', '$address')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Registration successful! Welcome to your dashboard.'); window.location.href='user_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
        }
    } else {
        // Passwords don't match if this code is reached
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PharmaPulse Registration</title>
    <!-- Font Awesome for Eye Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0f7fa, #01579b);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .reg-container {
            width: 100%;
            max-width: 450px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 30px;
            box-sizing: border-box;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .logo-container img {
            width: 70px;
            height: auto;
        }

        .form-box h2 {
            text-align: center;
            color: #01579b;
            margin-bottom: 20px;
            font-size: 22px;
        }

        .form-group {
            margin-bottom: 15px;
            position: relative;
        }

        label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 4px;
            font-size: 14px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        .toggle-password {
            position: absolute;
            top: 34px;
            right: 10px;
            color: #0288d1;
            cursor: pointer;
            font-size: 16px;
        }

        button {
            width: 100%;
            background-color: #0288d1;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: bold;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0277bd;
        }

        .login-link {
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
        }

        .login-link a {
            color: #01579b;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="reg-container">
        <div class="logo-container">
            <img src="pharma_logo.webp" alt="PharmaPulse Logo" />
        </div>

        <div class="form-box">
            <h2>Create Account</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required />
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required />
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required />
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
                </div>

                <div class="form-group">
                    <label for="mobile">Mobile Number</label>
                    <input type="tel" id="mobile" name="mobile" required />
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <button type="submit">Register</button>
                <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }
    </script>
</body>

</html>
