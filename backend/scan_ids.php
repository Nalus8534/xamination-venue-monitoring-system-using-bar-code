<?php
include 'session_check.php';
include 'db_connection.php'; // Include database connection

// Fetch venues from the database
// Assuming venues are stored in the 'venues' table with a 'venue' column
$query = "SELECT DISTINCT venue FROM venues ORDER BY venue"; // Ordered alphabetically
$result = $conn->query($query);
$venues = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $venues[] = htmlspecialchars($row['venue']); // Sanitize venue names
    }
}

// Close the database connection after fetching venues
$conn->close();
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2c2c3e;
            padding: 10px 20px;
            color: #e0e0e0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
        }

        .navbar h1 {
             margin: 0; /* Remove default margin */
        }

        main {
            flex: 1; /* Allows main content to grow */
            display: flex; /* Use Flexbox for side-by-side layout */
            flex-direction: column; /* Default to column on small screens */
            align-items: center; /* Center items horizontally when stacked */
            padding: 20px;
            width: 100%; /* Take full width */
            box-sizing: border-box; /* Include padding in width */
            gap: 20px; /* Add space between flex items */
        }

        .form-container,
        #studentDetails {
            background-color: #2c2c3e;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            width: 100%; /* Default to full width when stacked */
            max-width: 500px; /* Limit width on larger screens */
            text-align: left; /* Align labels to the left */
            box-sizing: border-box; /* Include padding in width */
        }

         /* Adjustments for side-by-side on larger screens */
         @media (min-width: 768px) {
             main {
                 flex-direction: row; /* Switch to row layout */
                 align-items: flex-start; /* Align items to the top */
                 justify-content: center; /* Center the two columns */
             }

             .form-container {
                 flex: 1; /* Allow form to take up some space */
                 max-width: 400px; /* Adjust max-width for form */
             }

             #studentDetails {
                 flex: 1; /* Allow details to take up some space */
                 max-width: 500px; /* Adjust max-width for details */
                 /* display: none;  Still hidden by default, shown by JS */
             }
         }


        .form-group {
            margin-bottom: 15px; /* Space between form groups */
        }

        .form-group label {
            display: block; /* Make labels block elements */
            margin-bottom: 5px; /* Space below label */
            font-weight: bold;
            color: #a0c0ff; /* Lighter blue for labels */
        }

        .form-group select,
        .form-group input[type="text"],
        .form-group input[type="file"] { /* Added file input */
            width: 100%; /* Make inputs full width */
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #444; /* Darker border */
            background-color: #3a3a4f; /* Slightly lighter background than body */
            color: #e0e0e0;
            box-sizing: border-box; /* Include padding in width */
        }

         /* Style for file input */
         .form-group input[type="file"] {
             background-color: #2c2c3e; /* Match container background */
             cursor: pointer;
         }


        .form-group input[type="text"]:read-only {
             background-color: #444; /* Different background for read-only */
             cursor: not-allowed;
        }

        button {
            width: 100%; /* Make button full width */
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px; /* Space above the button */
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Modified styling for the result message */
        #result {
            margin-top: 5px; /* Reduced margin to be closer to the input */
            margin-bottom: 15px; /* Space before the next form group */
            padding: 0; /* Removed padding */
            border-radius: 0; /* Removed border-radius */
            box-shadow: none; /* Removed box-shadow */
            font-size: 1em; /* Adjusted font size */
            font-weight: normal; /* Adjusted font weight */
            min-height: 1.2em; /* Ensure space even when empty */
            text-align: left; /* Align text to the left */
            color: #e0e0e0; /* Default text color */
        }

        /* Text colors for different result types - Increased specificity */
        #result.result-found {
            color: #28a745; /* Green text for found */
        }

        #result.result-not-found {
            color: #dc3545; /* Red text for not found */
        }

        #result.result-info {
             color: #ffc107; /* Yellow text for info/warnings */
        }

        /* Styling for the student details display area */
        #studentDetails {
            /* Existing styles for padding, border-radius, etc. */
            /* Added flex properties in @media query above */
        }

        #studentDetails h3 {
            margin-top: 0;
            color: #a0c0ff; /* Lighter blue */
            border-bottom: 2px solid #444; /* Separator line */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        #studentDetails p {
            margin-bottom: 10px; /* Space between detail lines */
            font-size: 1.1em;
            color: #e0e0e0; /* Light text */
        }

        #studentDetails p strong {
            color: #fff; /* White for labels */
            display: inline-block; /* Allows setting a width */
            width: 150px; /* Fixed width for labels */
            margin-right: 10px; /* Space between label and value */
        }

        /* Styling for the student image */
        #studentImage {
            max-width: 150px; /* Control image size */
            height: auto;
            border-radius: 8px; /* Optional: rounded corners for the image */
            margin-bottom: 20px; /* Space below the image */
            display: block; /* Make it a block element */
            margin-left: auto; /* Center the image */
            margin-right: auto; /* Center the image */
            border: 2px solid #444; /* Optional border */
        }


        footer {
            text-align: center;
            padding: 15px;
            background-color: #2c2c3e;
            color: #e0e0e0;
            width: 100%;
            font-size: 12px;
            line-height: 1.5;
            margin-top: auto; /* Push footer to the bottom */
            box-sizing: border-box;
        }

        footer a {
            color: #a0c0ff;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
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

         /* Responsive adjustments for form and details stacking */
         @media (max-width: 767px) {
             .navbar {
                  justify-content: center;
             }
             .navbar h1 {
                  width: 100%;
                  text-align: center;
                  margin-bottom: 10px;
             }
             .form-container, #studentDetails {
                  padding: 20px;
             }
             #studentDetails p strong {
                 width: 120px; /* Adjust label width on smaller screens */
             }
              footer .footer-content {
                 flex-direction: column;
              }
              footer img {
                  margin: 10px 0;
              }
         }


    </style>
     <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
</head>
<body>
    <div class="navbar">
        <h1>Scan IDs</h1>
    </div>
    <main>
        <div class="form-container">
            <div class="form-group">
                <label for="venue">Select Venue:</label>
                <select id="venue">
                    <option value="">-- Select Venue --</option>
                    <?php foreach ($venues as $venue): ?>
                        <option value="<?php echo $venue; ?>"><?php echo $venue; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div id="fileUploadSection" class="form-group">
                <label for="idImage">Choose ID Image to Scan Barcode:</label>
                <input type="file" id="idImage" accept="image/*">
                <div id="barcodeStatus"></div>
            </div>

            <div class="form-group">
                <label for="scannedId">After Scanning ID (or use physical scanner):</label>
                <input type="text" id="scannedId" placeholder="Scan or enter ID here" readonly>
            </div>

            <div id="result"></div>

            <div class="form-group">
                <label for="admissionNumber">Enter Admission Number Manually:</label>
                <input type="text" id="admissionNumber" placeholder="Enter Admission Number">
            </div>

            <button id="searchButton">Search Manually</button>
        </div>

        <div id="studentDetails">
            <h3>Student Information</h3>
            <img id="studentImage" src="" alt="Student Photo" style="display: none;">

            <p><strong>Full Name:</strong> <span id="detailFullName"></span></p>
            <p><strong>Admission No:</strong> <span id="detailAdmissionNumber"></span></p>
            <p><strong>NTA Level:</strong> <span id="detailNTALevel"></span></p>
            <p><strong>Exam Number:</strong> <span id="detailExamNumber"></span></p>
            <p><strong>Program:</strong> <span id="detailProgram"></span></p>
            <p><strong>Venue:</strong> <span id="detailVenue"></span></p>
        </div>


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

    <script>
        // Function to clear the result message after a delay
        let resultTimeout;
        function clearResultMessage() {
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = '';
            resultDiv.className = ''; // Clear classes
        }

        function displayResult(message, type = 'info') {
             const resultDiv = document.getElementById('result');
             resultDiv.textContent = message;
             // Remove previous classes that might add background/padding
             resultDiv.classList.remove('result-found', 'result-not-found', 'result-info');
             // Add class for text color
             resultDiv.classList.add('result-' + type);

             // Clear previous timeout if any
             if (resultTimeout) {
                 clearTimeout(resultTimeout);
             }
             // Set a timeout to clear the message after 5 seconds
             resultTimeout = setTimeout(clearResultMessage, 5000); // 5000 milliseconds = 5 seconds
        }

        // Function to clear the student details display area and image
        function clearStudentDetails() {
            document.getElementById('detailFullName').textContent = '';
            document.getElementById('detailAdmissionNumber').textContent = '';
            document.getElementById('detailNTALevel').textContent = '';
            document.getElementById('detailExamNumber').textContent = '';
            document.getElementById('detailProgram').textContent = '';
            document.getElementById('detailVenue').textContent = '';
            // *** ADDED: Clear image source and hide image ***
            const studentImage = document.getElementById('studentImage');
            studentImage.src = '';
            studentImage.style.display = 'none';

            document.getElementById('studentDetails').style.display = 'none'; // Hide the container
        }

        // Function to display student details and image
        function showStudentDetails(student) {
            document.getElementById('detailFullName').textContent = student.full_name;
            document.getElementById('detailAdmissionNumber').textContent = student.admission_number;
            document.getElementById('detailNTALevel').textContent = student.nta_level;
            document.getElementById('detailExamNumber').textContent = student.exam_number;
            document.getElementById('detailProgram').textContent = student.program;
            document.getElementById('detailVenue').textContent = student.venue;

            // *** ADDED: Display student image if path exists ***
            const studentImage = document.getElementById('studentImage');
            if (student.image_path) {
                 // Assuming image_path is relative to the document root or a web-accessible folder
                 studentImage.src = student.image_path;
                 studentImage.style.display = 'block'; // Show the image
                 // Handle broken image link
                 studentImage.onerror = function() {
                     console.error("Failed to load image:", student.image_path);
                     studentImage.style.display = 'none'; // Hide if image fails to load
                 };
            } else {
                 studentImage.src = ''; // Clear previous image
                 studentImage.style.display = 'none'; // Hide if no image path
            }


            document.getElementById('studentDetails').style.display = 'block'; // Show the container
        }


        function searchStudent(admissionNumberToSearch) {
            const venue = document.getElementById('venue').value;
            // Use the provided admissionNumberToSearch, or fall back to the manual input field
            const admissionNumber = admissionNumberToSearch || document.getElementById('admissionNumber').value;

            // Clear previous result message and student details immediately
            clearResultMessage();
            clearStudentDetails();


            if (!venue) {
                displayResult("Please select a venue.", 'info');
                return;
            }

            if (!admissionNumber) {
                 displayResult("Please enter or scan an admission number.", 'info');
                return;
            }

            // Display searching message
            displayResult("Searching...", 'info');


            // Proceed with the fetch request
            fetch(`search_student_by_id.php?venue=${encodeURIComponent(venue)}&admission_number=${encodeURIComponent(admissionNumber)}`)
                .then(response => {
                    if (!response.ok) {
                        // Handle HTTP errors (e.g., 404, 500)
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.found) {
                        displayResult(`Student Found: ${data.full_name}`, 'found'); // Use full_name from response
                        showStudentDetails(data); // Display all details, including image_path
                        // Optionally clear the scanned/manual input field after successful search
                        // document.getElementById('scannedId').value = '';
                        // document.getElementById('admissionNumber').value = '';
                    } else {
                        // Check if the server returned a specific error message
                         if (data.error) {
                              displayResult(`Error: ${data.error}`, 'not-found'); // Use not-found style for errors too
                         } else if (data.message) {
                              displayResult(data.message, 'not-found'); // Display specific not found message
                         }
                         else {
                              displayResult(`Student with Admission Number "${admissionNumber}" not found in Venue "${venue}".`, 'not-found');
                         }
                         clearStudentDetails(); // Ensure details are hidden if not found
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    displayResult("An error occurred while searching.", 'not-found'); // Use not-found style for fetch errors
                    clearStudentDetails(); // Ensure details are hidden on error
                });
        }

        // Add event listener for the scannedId input field
        const scannedIdInput = document.getElementById('scannedId');
        if (scannedIdInput) { // Check if the element exists before adding listener

             // --- Keypress listener for external scanners (detecting Enter) ---
             // This is the primary way to capture input from a physical scanner
             scannedIdInput.addEventListener('keypress', function(event) {
                 // Check if the pressed key is 'Enter' (key code 13 or key string 'Enter')
                 if (event.key === 'Enter' || event.keyCode === 13) {
                     event.preventDefault(); // Prevent default form submission or newline
                     const scannedValue = this.value;
                     if (scannedValue) { // Only search if there's a value
                         console.log('Enter keypress triggered search:', scannedValue);
                         searchStudent(scannedValue);
                         this.value = ''; // Clear the field after searching
                     }
                 }
             });

             // The 'input' event listener is removed as the keypress listener handles scanner input
             // and the file upload populates the field directly.

        } else {
            console.error("Element with ID 'scannedId' not found.");
        }


        // Add event listener for the manual search button click
        const searchButton = document.getElementById('searchButton');
        if (searchButton) { // Check if the element exists before adding listener
             searchButton.addEventListener('click', function() {
                 // Trigger search using the value from the manual input field
                 searchStudent(document.getElementById('admissionNumber').value);
             });
        } else {
             console.error("Element with ID 'searchButton' not found.");
        }


        // --- File Upload Barcode Scanning (using QuaggaJS) ---
        const idImageInput = document.getElementById('idImage');
        const barcodeStatusDiv = document.getElementById('barcodeStatus'); // Status for file upload

        if (idImageInput) {
            idImageInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageUrl = e.target.result;
                        barcodeStatusDiv.textContent = 'Scanning barcode from image...';
                        clearStudentDetails(); // Clear previous details
                        clearResultMessage(); // Clear previous message

                        // Use Quagga.decodeSingle for static images
                        Quagga.decodeSingle({
                            src: imageUrl,
                            numOfWorkers: 0, // Needs to be 0 for file input
                            locate: true, // Attempt to locate the barcode in the image
                            inputStream: { size: 1000, singleChannel: true },
                             decoder: {
                                 readers: [
                                     "code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader",
                                     "code_39_vin_reader", "codabar_reader", "upc_reader", "upc_e_reader",
                                     "i2of5_reader"
                                 ]
                             },
                             locator: { patchSize: "medium", halfSample: true }
                        }, function(result) {
                             idImageInput.value = ''; // Clear the file input value

                            if (result && result.codeResult) {
                                const decodedText = result.codeResult.code;
                                console.log('Barcode Decoded (Image):', decodedText);
                                document.getElementById('scannedId').value = decodedText;
                                barcodeStatusDiv.textContent = `Barcode Scanned: ${decodedText}`;
                                searchStudent(decodedText); // Trigger search with decoded text
                            } else {
                                console.warn('Barcode not detected or could not be decoded (Image).');
                                barcodeStatusDiv.textContent = 'Barcode not found or could not be decoded from image. Try a clearer image or adjust parameters.';
                                displayResult("Could not scan barcode from image. Please enter Admission Number manually.", 'not-found');
                            }
                        });
                    };
                    reader.readAsDataURL(file); // Read the file as a data URL
                } else {
                     idImageInput.value = ''; // Clear the input if no file is selected
                }
            });
        } else {
            console.error("Element with ID 'idImage' not found.");
        }

        // Initial setup - ensure file upload section is visible and scannedId is readonly
        document.getElementById('fileUploadSection').style.display = 'block';
        document.getElementById('scannedId').readOnly = true; // Keep scannedId readonly for scanned input
        document.getElementById('scannedId').placeholder = "Scan barcode or choose image";


    </script>
</body>
</html>
