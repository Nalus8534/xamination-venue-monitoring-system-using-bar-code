<?php
include 'session_check.php';
include 'assign_venues_handler.php';
// Include database connection if needed for other parts of the page,
// though assign_venues_handler.php already includes it for POST handling.
// include 'db_connection.php';

// Define the list of programs from the provided document
$programs = [
    "Ordinary Diploma In Automotive Engineering",
    "Ordinary Diploma In Auto-electrical And Electronics Engineering",
    "Ordinary Diploma In Civil And Highway Engineering",
    "Ordinary Diploma In Civil And Irrigation Engineering",
    "Ordinary Diploma In Civil Engineering",
    "Ordinary Diploma In Computer Science",
    "Ordinary Diploma In Cyber Security And Digital Forensic",
    "Ordinary Diploma In Electrical And Biomedical Engineering",
    "Ordinary Diploma In Electrical And Hydro Power Engineering",
    "Ordinary Diploma In Electrical And Solar Energy Engineering",
    "Ordinary Diploma In Electrical And Wind Energy Engineering",
    "Ordinary Diploma In Electrical Engineering",
    "Ordinary Diploma In Electronics And Telecommunication Engineering",
    "Ordinary Diploma In Heavy Duty Equipment Engineering",
    "Ordinary Diploma In Information Technology",
    "Ordinary Diploma In Laboratory Science And Technology",
    "Ordinary Diploma In Mechanical And Bio-ernergy Engineering",
    "Ordinary Diploma In Mechanical Engineering",
    "Ordinary Diploma In Pipe Works,oil And Gas Engineering",
    "Ordinary Dipolma In Instrumentation Engineering"
];

// You might also want to fetch venues dynamically from the database here
// if they are not static, similar to how it's done in scan_ids.php.
// For now, using the static list from your original assign_venues.php.
$venues = [
    "DH", "R12/13", "G 11", "F 12", "S 06", "S 07", "S 10", "T 10", "T 11", "T 12", "UG 06", "UG 07", "UF01", "US 02", "H/WAY"
];

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
            display: flex; /* Use flexbox for layout */
            flex-direction: column; /* Stack children vertically */
            min-height: 100vh; /* Ensure body takes at least full viewport height */
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
            flex-wrap: wrap; /* Allow wrapping */
            box-sizing: border-box; /* Include padding in width */
        }

        .navbar h1 {
            margin: 0; /* Remove default margin */
        }

        main {
            flex: 1; /* Allows main content to grow and push footer down */
            display: flex;
            justify-content: center; /* Center form horizontally */
            align-items: flex-start; /* Align items to the top */
            padding: 20px;
            width: 100%; /* Take full width */
            box-sizing: border-box; /* Include padding in width */
        }


        form {
            max-width: 600px;
            width: 100%; /* Make form responsive */
            padding: 20px;
            background-color: #2c2c3e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            box-sizing: border-box; /* Include padding in width */
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #a0c0ff; /* Lighter blue for labels */
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
            box-sizing: border-box; /* Include padding in width */
        }

        form button {
            background-color: #007bff;
            color: white; /* Button text color */
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Style for the message div */
        form div {
            margin-top: -10px; /* Adjust margin to be closer to the button */
            margin-bottom: 20px; /* Space below the message */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        form div[style*='color: green'] {
            background-color: #28a745; /* Green background for success */
            color: white !important; /* Ensure text is white */
        }

        form div[style*='color: red'] {
            background-color: #dc3545; /* Red background for error */
            color: white !important; /* Ensure text is white */
        }


        footer {
            text-align: center;
            padding: 15px; /* Adjusted padding */
            background-color: #2c2c3e;
            color: #e0e0e0;
            width: 100%;
            font-size: 12px; /* Smaller font size */
            line-height: 1.5; /* Adjusted line height */
            margin-top: 20px; /* Space above footer */
            box-sizing: border-box; /* Include padding in width */
        }

         footer .footer-content {
             display: flex;
             align-items: center;
             justify-content: space-between;
             max-width: 1200px; /* Limit width */
             margin: 0 auto; /* Center content */
             flex-wrap: wrap; /* Allow wrapping */
         }
         footer img {
            height: 35px; /* Slightly smaller logo */
            margin: 5px 15px; /* Adjust margin */
         }
         footer .footer-text {
             text-align: center;
             flex-grow: 1; /* Allow text to take available space */
             margin: 5px 10px;
         }
        footer .footer-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

         footer a {
             color: #a0c0ff; /* Lighter blue for links */
             text-decoration: none;
         }
         footer a:hover {
             text-decoration: underline;
         }

         /* Responsive adjustments */
         @media (max-width: 600px) {
             .navbar {
                  justify-content: center;
             }
             .navbar h1 {
                  width: 100%;
                  text-align: center;
                  margin-bottom: 10px;
             }
             form {
                  padding: 15px;
             }
              footer .footer-content {
                 flex-direction: column;
              }
              footer img {
                  margin: 10px 0;
              }
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
                <?php
                // Loop through the $programs array to generate options
                foreach ($programs as $program_name) {
                    echo '<option value="' . htmlspecialchars($program_name) . '">' . htmlspecialchars($program_name) . '</option>';
                }
                ?>
            </select>

            <label for="venue">Select Venue:</label>
            <select id="venue" name="venue" required>
                <option value="">-- Select Venue --</option>
                 <?php
                // Loop through the $venues array to generate options
                foreach ($venues as $venue_name) {
                    echo '<option value="' . htmlspecialchars($venue_name) . '">' . htmlspecialchars($venue_name) . '</option>';
                }
                ?>
            </select>

            <button type="submit">Assign Venue</button>
            <?php echo $message; // Display success or error message ?>
        </form>
    </main>
    <footer>
        <div class="footer-content">
            <img src="../frontend/ATC LOGO.png" alt="ATC Logo">

            <div class="footer-text">
                <div class="footer-title">Arusha Technical College</div>
                JUNCTION OF MOSHI-ARUSHA AND NAIROBI ROADS<br>
                P.O. BOX 296, ARUSHA-TANZANIA<br>
                TELEPHONE: +255-27-2503040/2502076, FAX: +255-27-2548337<br>
                WEBSITE: <a href="http://www.atc.ac.tz" target="_blank">http://www.atc.ac.tz</a>,
                E-MAIL: <a href="mailto:rector@atc.ac.tz">rector@atc.ac.tz</a>
            </div>

            <img src="../frontend/ATC LOGO.png" alt="ATC Logo">
        </div>
    </footer>
</body>
</html>
