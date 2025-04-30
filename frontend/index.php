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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2); /* Thin inner shadow */
        }

        form h1 {
            font-family: cursive;
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
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555; /* Slightly darker label color */
        }

        /* General styling for text inputs within the form */
        form input[type="text"] {
             width: 100%; /* Make input fill the container */
             padding: 12px;
             margin-bottom: 20px;
             border: 1px solid #ccc;
             border-radius: 5px;
             font-size: 16px;
             box-sizing: border-box; /* Include padding and border in the element's total width and height */
             transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Add transition for focus effect */
        }

        /* Container for password input and toggle span */
        .password-container {
            position: relative; /* Needed for absolute positioning of the span */
            margin-bottom: 20px; /* Match the margin of other form groups */
        }

        /* Styling for password input within the container */
        .password-container input[type="password"],
        .password-container input[type="text"] { /* Style for both password and text types */
            width: 100%; /* Make input fill the container */
            padding: 12px;
            padding-right: 35px; /* Add padding to the right for the icon */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Include padding and border in the element's total width and height */
            transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Add transition for focus effect */
        }

        /* Focus effect for inputs */
        form input[type="text"]:focus,
        .password-container input[type="password"]:focus,
        .password-container input[type="text"]:focus {
            outline: none; /* Remove default outline */
            border-color: #007bff; /* Highlight border color on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add a subtle shadow on focus */
        }


        .password-container .toggle-password {
            position: absolute;
            right: 10px; /* Position from the right edge of the container */
            top: 50%; /* Vertically center the span */
            transform: translateY(-50%); /* Adjust for perfect vertical centering */
            cursor: pointer;
            color: #666; /* Color of the toggle icon */
            font-size: 16px; /* Adjust icon size as needed */
            user-select: none; /* Prevent text selection */
            transition: color 0.3s ease; /* Add transition for hover effect */
        }

        .password-container .toggle-password:hover {
            color: #333; /* Darker color on hover */
        }


        form button {
            width: 100%; /* Ensures the button spans the full width of the container */
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
        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <span class="toggle-password" id="togglePassword">
                <i class="fas fa-eye"></i> </span>
        </div>

        <button type="submit">Login</button>
    </form>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = togglePassword ? togglePassword.querySelector('i') : null; // Get the icon element

        if (togglePassword && passwordInput && eyeIcon) {
            togglePassword.addEventListener('click', function() {
                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the icon class
                if (type === 'password') {
                    eyeIcon.classList.remove('fa-eye-slash'); // Remove slash icon
                    eyeIcon.classList.add('fa-eye'); // Add eye icon
                } else {
                    eyeIcon.classList.remove('fa-eye'); // Remove eye icon
                    eyeIcon.classList.add('fa-eye-slash'); // Add slash icon
                }
            });
        } else {
            console.error("Password input, toggle span, or icon not found.");
        }
    </script>
</body>
</html>
