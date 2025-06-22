<?php
session_start();

$admin_email = "admin@pharmapulse.com";
$admin_password = "admin123";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admindashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            
            .login-container {
                background: #fff;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                text-align: center;
                width: 300px;
            }
            
            .input-group {
                margin: 15px 0;
                text-align: left;
            }
            
            .input-group label {
                display: block;
                font-size: 14px;
                margin-bottom: 5px;
                color: #333;
            }
            
            .input-group input {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
            }
            
            .login-btn {
                width: 100%;
                padding: 10px;
                border: none;
                background: #007bff;
                color: white;
                font-size: 16px;
                cursor: pointer;
                border-radius: 5px;
                margin-top: 10px;
            }
            
            .login-btn:hover {
                background: #27ae60;
            }
            
            p a {
                text-decoration: none;
                color: #007bff;
                font-size: 14px;
            }
            
            p a:hover {
                text-decoration: underline;
            }
            
            .error {
                color: red;
                margin-top: 10px;
                font-size: 14px;
            }
        </style>
    </head>

    <body>
        <div class="login-container">
            <h2>Admin Login</h2>

            <?php if (!empty($error)): ?>
            <div class="error">
                <?= $error ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
        </div>
    </body>

    </html>