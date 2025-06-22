<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "Pooja@2004";
$dbname = "pharmapulse_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password_input = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password_input, $user["user_password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            echo "<script>window.location.href='user_dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Incorrect password!'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Email not registered! Please sign up.'); window.location.href='registration.php';</script>";
        exit();
    }
}

$conn->close();
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login Page</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
        <style>
            body {
                background: linear-gradient(to right, #e0f7fa, #01579b);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: 'Segoe UI', sans-serif;
            }
            
            .login-container {
                background-color: white;
                padding: 40px;
                border-radius: 20px;
                box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
                text-align: center;
                width: 350px;
                box-sizing: border-box;
            }
            
            .logo-container img {
                width: 70px;
                margin-bottom: 15px;
            }
            
            .login-container h2 {
                color: #01579b;
                font-size: 24px;
                margin-bottom: 20px;
            }
            
            .form-group {
                position: relative;
                margin-bottom: 20px;
            }
            
            .input-box {
                width: 100%;
                padding: 12px 45px 12px 15px;
                /* space for eye icon */
                border: 1px solid #ccc;
                border-radius: 25px;
                font-size: 16px;
                background: #f5f5f5;
                outline: none;
                box-sizing: border-box;
            }
            
            .toggle-password {
                position: absolute;
                right: 15px;
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
                color: #01579b;
                font-size: 18px;
                transition: color 0.3s ease;
            }
            
            .toggle-password:hover {
                color: #0288d1;
            }
            
            .login-btn {
                width: 100%;
                padding: 12px;
                border: none;
                border-radius: 25px;
                font-size: 18px;
                font-weight: bold;
                color: white;
                background: #0288d1;
                cursor: pointer;
                transition: 0.3s ease;
            }
            
            .login-btn:hover {
                background: #0277bd;
            }
            
            .register-link {
                margin-top: 15px;
                font-size: 14px;
                color: #333;
            }
            
            .register-link a {
                color: #01579b;
                font-weight: bold;
                text-decoration: none;
            }
            
            .register-link a:hover {
                text-decoration: underline;
            }
        </style>
    </head>

    <body>

        <div class="login-container">
            <div class="logo-container">
                <img src="pharma_logo.webp" alt="PharmaPulse Logo" />
            </div>
            <h2>Login</h2>
            <form action="#" method="POST">
                <div class="form-group">
                    <input type="text" class="input-box" name="email" placeholder="Username" required />
                </div>
                <div class="form-group">
                    <input type="password" class="input-box" id="password" name="password" placeholder="Password" required />
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword(this)"></i>
                </div>
                <button class="login-btn" type="submit">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="registration.php">Register here</a></p>
        </div>

        <script>
            function togglePassword(icon) {
                const input = document.getElementById("password");
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        </script>

    </body>

    </html>