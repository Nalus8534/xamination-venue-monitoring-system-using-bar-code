<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: ../frontend/index.php'); // Redirect to login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #1e1e2f; /* Dark background */
            color: #e0e0e0; /* Light text for contrast */
            overflow-x: hidden; /* Prevent horizontal scroll */
            display: flex;
            flex-direction: column;
            overflow-y: auto; /* Allow vertical scroll if needed */
            height: 100vh; /* Full viewport height */
        }

        /* Top Navigation Bar */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2c2c3e; /* Darker shade for navbar */
            padding: 10px 20px;
            color: #e0e0e0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .navbar .logo {
            display: flex;
            align-items: center;
        }

        .navbar .logo img {
            max-width: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .navbar h1 {
            font-size: 24px;
            margin: 0;
        }

        /* Navigation Tabs Section */
        .nav-tabs {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 150px); /* Adjust height to center tabs */
            width: 100%;
            gap: 20px; /* Space between tabs */
        }

        .nav-tabs a {
            display: inline-block;
            width: 250px; /* Increased width for larger buttons */
            padding: 20px; /* Increased padding for larger buttons */
            background-color: #3a3a4f; /* Dark button background */
            color: #e0e0e0; /* Light text */
            text-decoration: none;
            font-size: 18px; /* Larger font size */
            font-weight: bold;
            text-align: center;
            border-radius: 8px; /* Slightly rounded corners */
            transition: all 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        .nav-tabs a:hover {
            background-color: #50506b; /* Slightly lighter on hover */
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.7);
            transform: scale(1.1); /* Zoom-in effect */
        }

        .nav-tabs a.logout-btn {
            background-color: #a71d2a; /* Darker red for logout button */
        }

        .nav-tabs a.logout-btn:hover {
            background-color: #d9534f; /* Slightly lighter red on hover */
            transform: scale(1.1); /* Zoom-in effect */
        }

        /* Footer Section */
        footer {
            position: relative;
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
            margin: 0 40px; /* Space between logos and text */
            height: 70px; /* Small logo size for the footer */
            width: 70px; /* Small logo size for the footer */
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="navbar">
        <div class="logo">
            <img src="../frontend/ATC LOGO.png" alt="College Logo">
            <h1>Admin Dashboard</h1>
        </div>
    </div>

    <!-- Navigation Tabs Section -->
    <div class="nav-tabs">
        <a href="scan_ids.php">Scan IDs</a>
        <a href="view_students.php">View Registered Students</a>
        <a href="assign_venues.php">Assign Venues</a>
        <a href="upload_image.php">Upload Student Image</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Footer Section -->
    <footer>
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <!-- Left Logo -->
            <img src="../frontend/ATC LOGO.png" alt="ATC Logo" style="height: 70px; width: 70px;">

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
            <img src="../frontend/ATC LOGO.png" alt="ATC Logo" style="height: 70px; width: 70px;">
        </div>
    </footer>
</body>
</html>