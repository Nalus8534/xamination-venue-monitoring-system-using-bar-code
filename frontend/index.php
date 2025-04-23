<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\frontend\index.php -->
<?php
session_start();
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: ../backend/dashboard.php'); // Redirect to the dashboard if logged in
    exit();
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
            margin: 0;
            padding: 0;
            background: linear-gradient(180deg,rgb(0, 70, 144),rgb(70, 132, 199), rgb(9, 41, 76));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }

        form {
            max-width: 400px;
            width: 400px;
            padding: 40px;
            border-radius: 10px;
            background-color: #fff;
            color: #333;
            animation: fadeIn 1s ease-in-out;
        }

        form h1 {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            color:rgb(16, 49, 83);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-width: 100px;
            border-radius: 50%; /* Optional: Makes the logo circular */
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input {
            width: calc(100% - 0px); /* Ensures the input spans the full width of the container */
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button {
            width: calc(100% - 0px); /* Ensures the button spans the full width of the container */
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <form action="../backend/login.php" method="POST">
        <h1>Admin Login</h1>
        <div class="logo-container">
            <img src="../frontend/ATC LOGO.png" alt="College Logo">
        </div>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>