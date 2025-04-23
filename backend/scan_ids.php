<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\scan_ids.php -->
<?php
include 'session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan IDs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #1e1e2f; /* Dark background */
            color: #e0e0e0; /* Light text for contrast */
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2c2c3e;
            padding: 10px 20px;
            color: #e0e0e0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        main {
            text-align: center;
            margin: 50px auto;
        }

        main button {
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        main button:hover {
            background-color: #0056b3;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #2c2c3e;
            color: #e0e0e0;
            width: 100%;
            font-size: 14px;
            line-height: 1.6;
            margin-top: 20px; /* Adds space between the form and footer */
        }

        footer a {
            color: #007bff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        footer img {
            height: 40px; /* Small logo size for the footer */
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Scan IDs</h1>
    </div>
    <main>
        <p>Use the scanner to verify student details.</p>
        <button>Start Scanning</button>
    </main>
    <footer>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <!-- Left Logo -->
            <img src="../frontend/ATC LOGO.png" alt="ATC Logo" style="height: 40px;">

            <!-- Footer Content -->
            <div style="text-align: center;">
                <div class="footer-title">Arusha Technical College</div>
                JUNCTION OF MOSHI-ARUSHA AND NAIROBI ROADS<br>
                P.O. BOX 296, ARUSHA-TANZANIA<br>
                TELEPHONE: +255-27-2503040/2502076, FAX: +255-27-2548337<br>
                WEBSITE: <a href="http://www.atc.ac.tz" target="_blank">http://www.atc.ac.tz</a>, 
                E-MAIL: <a href="mailto:rector@atc.ac.tz">rector@atc.ac.tz</a>
            </div>

            <!-- Right Logo -->
            <img src="../frontend/ATC LOGO.png" alt="ATC Logo" style="height: 40px;">
        </div>
    </footer>
</body>
</html>