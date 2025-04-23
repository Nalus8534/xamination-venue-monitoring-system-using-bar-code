<!-- filepath: c:\xampp\htdocs\examination-venue-monitoring-system\backend\assign_venues.php -->
<?php
include 'session_check.php';
include 'assign_venues_handler.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Venues</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #1e1e2f; /* Dark background */
            color: #e0e0e0; /* Light text for contrast */
            overflow-x: hidden; /* prevent horizontal scroll */
        }

        .navbar {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: space-between;
            background-color: #2c2c3e;
            padding: 10px 20px;
            color: #e0e0e0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        form {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #2c2c3e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input, form select, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 5px;
            font-size: 16px;
            background-color: #3a3a4f;
            color: #e0e0e0;
        }

        form button {
            background-color: #007bff;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
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
        <h1>Assign Venues</h1>
    </div>
    <main>
        <form action="assign_venues.php" method="POST">
            <label for="full_name">Student Full Name:</label>
            <input type="text" id="full_name" name="full_name" placeholder="Enter full name" required>

            <label for="admission_number">Admission Number:</label>
            <input type="text" id="admission_number" name="admission_number" placeholder="Enter admission number" required>

            <label for="nta_level">NTA Level:</label>
            <select id="nta_level" name="nta_level" required>
                <option value="">-- Select NTA Level --</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7-1">7-1</option>
                <option value="7-2">7-2</option>
                <option value="8">8</option>
            </select>

            <label for="exam_number">Exam Number:</label>
            <input type="text" id="exam_number" name="exam_number" placeholder="Enter exam number" required>

            <label for="program">Program/Course:</label>
            <select id="program" name="program" required>
                <option value="">-- Select Program --</option>
                <option value="Computer Science">Computer Science</option>
                <option value="Civil Engineering">Civil Engineering</option>
                <option value="Electrical Engineering">Electrical Engineering</option>
                <option value="Mechanical Engineering">Mechanical Engineering</option>
                <option value="Business Administration">Business Administration</option>
            </select>

            <label for="venue">Select Venue:</label>
            <select id="venue" name="venue" required>
                <option value="">-- Select Venue --</option>
                <option value="DH">DH</option>
                <option value="R 12/13">R 12/13</option>
                <option value="G 11">G 11</option>
                <option value="F 12">F 12</option>
                <option value="S 06">S 06</option>
                <option value="S 07">S 07</option>
                <option value="S 10">S 10</option>
                <option value="T 10">T 10</option>
                <option value="T 11">T 11</option>
                <option value="T 12">T 12</option>
                <option value="UG 06">UG 06</option>
                <option value="UG 07">UG 07</option>
                <option value="UF 01">UF 01</option>
                <option value="US 02">US 02</option>
                <option value="H/WAY">H/WAY</option>
            </select>

            <button type="submit">Assign Venue</button>
            <?php echo $message; // Display success or error message ?>
        </form>
    </main>
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