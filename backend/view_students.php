<?php
// view_students.php
// Ensure session handling is correctly implemented if needed
// include 'session_check.php';
include 'db_connection.php';

// This PHP function seems unused in the context of displaying data fetched later via JS.
// function formatFullName($firstName, $middleName, $lastName)
// {
//     $fullName = trim($firstName . ' ' . $middleName . ' ' . $lastName);
//     return $fullName;
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Registered Students</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            /* height: 100vh; Remove fixed height for better flexibility */
            padding: 0;
            background: #1e1e2f;
            color: #e0e0e0;
            display: flex;
            /* overflow-x: hidden; Allow horizontal scroll if table is wide */
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
            padding: 10px; /* Add some padding */
            width: 100%;
            box-sizing: border-box; /* Include padding in width */
            overflow-x: auto; /* Allow horizontal scrolling for the main content if needed */
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #2c2c3e;
            padding: 10px 20px;
            color: #e0e0e0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
            flex-wrap: wrap; /* Allow navbar items to wrap on smaller screens */
        }

        .navbar h1 {
            margin: 0 10px 10px 0; /* Adjust margin for wrapping */
            flex-shrink: 0; /* Prevent title from shrinking too much */
        }

        .upload-area {
            display: flex;
            align-items: center;
           /* Removed justify-content: space-between; */
            margin-bottom: 10px; /* Add margin for spacing */
            margin-right: 10px; /* Space between upload and search */
        }

        .upload-area form {
             display: flex;
             align-items: center;
        }

        .upload-area input[type="file"] {
            padding: 8px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: transparent;
            color: #e0e0e0;
            font-size: 14px;
            margin-right: 5px; /* Space between file input and button */
        }

        .upload-area button {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .upload-area button:hover {
            background-color: #0056b3;
        }

        .progress-section { /* Container for progress bar and extracted text */
             width: 100%;
             margin-top: 10px;
             padding: 0 10px; /* Add padding to align with main content */
             box-sizing: border-box;
        }

        .progress-bar {
            width: 100%; /* Make progress bar full width */
            /* margin: 10px auto; */ /* Removed auto margin */
            background-color: #444;
            border-radius: 5px;
            overflow: hidden;
            display: none; /* Hidden by default */
            margin-bottom: 10px; /* Space below progress bar */
        }

        .progress-bar div {
            height: 20px;
            background-color: #007bff;
            width: 0%;
            text-align: center;
            color: white;
            line-height: 20px;
            font-size: 12px;
        }

        .extracted-text {
             margin: 10px 0;
             width: 100%; /* Make textarea full width */
        }

        .extracted-text h3 {
            margin-bottom: 5px;
            font-size: 1em;
        }

        .extracted-text textarea {
            width: 100%;
            box-sizing: border-box; /* Include padding/border in width */
            padding: 10px;
            font-size: 14px;
            border: 1px solid #444; /* Darker border */
            border-radius: 5px;
            background-color: #2c2c3e; /* Match background */
            color: #e0e0e0; /* Match text color */
            min-height: 100px; /* Minimum height */
        }

        .search-bar {
            display: flex;
            align-items: center;
             margin-bottom: 10px; /* Add margin for spacing */
        }

        .search-bar input {
            padding: 8px 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: transparent; /* Keep transparent */
            color: #e0e0e0;
            font-size: 14px;
            /* margin-left: 10px; Remove fixed margin */
        }

        .search-bar input::placeholder {
            color: #aaa;
        }

        table {
            width: 100%; /* Use full width */
            margin: 20px 0; /* Adjust margin */
            border-collapse: collapse;
            background-color: #2c2c3e;
            color: #e0e0e0;
            table-layout: auto; /* Auto adjust column widths */
            font-size: 0.9em; /* Slightly smaller font for table */
        }

        table th,
        table td {
            border: 1px solid #444;
            padding: 10px; /* Adjust padding */
            text-align: left;
            white-space: nowrap; /* Prevent text wrapping initially */
        }

        table th {
            background-color: rgb(0, 42, 86);
            color: white;
            text-transform: uppercase;
            position: sticky; /* Make header sticky */
            top: 0; /* Stick to the top */
            z-index: 1; /* Ensure header is above scrolling content */
        }

        table tr:nth-child(even) {
            background-color: #3a3a4f;
        }

        table tr:hover {
            background-color: #50506b;
        }

        /* Allow wrapping for specific columns if needed */
         table td:nth-child(1), /* Full Name */
         table td:nth-child(5)  /* Program */
         {
            white-space: normal;
        }

        footer {
            text-align: center;
            padding: 15px; /* Adjust padding */
            background-color: #2c2c3e;
            color: #e0e0e0;
            width: 100%;
            font-size: 12px; /* Smaller font size */
            line-height: 1.5; /* Adjust line height */
            margin-top: 20px;
           /* position: relative; */ /* Can remove relative positioning */
            box-sizing: border-box;
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
         @media (max-width: 768px) {
            .navbar {
                 justify-content: center; /* Center items when wrapped */
            }
            .navbar h1 {
                 width: 100%;
                 text-align: center;
                 margin-bottom: 15px;
            }
             .upload-area, .search-bar {
                 width: 100%;
                 justify-content: center;
                 margin-right: 0;
             }
             .search-bar input {
                 width: 80%; /* Make search input wider */
             }
             table th, table td {
                 padding: 8px;
                 font-size: 0.85em;
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
        <h1>View Registered Students</h1>
        <div class="upload-area">
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="file" name="pdfFile" id="pdfFile" accept=".pdf">
                <button type="button" id="uploadButton">Upload & Process PDF</button>
            </form>
        </div>
         <div class="search-bar">
             <input type="text" id="search" placeholder="Search existing students...">
         </div>
         </div>

     <div class="progress-section">
         <div class="progress-bar" id="progressBar">
             <div></div>
         </div>
         <div class="extracted-text" id="extractedTextContainer" style="display: none;"> <h3>Extracted Text (for review)</h3>
             <textarea id="extractedText" rows="8" readonly></textarea>
         </div>
     </div>

    <main>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Admission Number</th>
                    <th>NTA Level</th>
                    <th>Exam Number</th>
                    <th>Program</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody id="results">
                <?php
                // Fetch initial data from DB on page load
                try {
                    $sql = "SELECT full_name, admission_number, nta_level, exam_number, program, venue FROM venues ORDER BY full_name"; // Added ORDER BY
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['full_name'] ?? '') . "</td>"; // Use null coalescing for safety
                            echo "<td>" . htmlspecialchars($row['admission_number'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['nta_level'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['exam_number'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['program'] ?? '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['venue'] ?? '') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center;'>No students found in the database. Upload a PDF to add students.</td></tr>";
                    }
                } catch (Exception $e) {
                    // Log error securely
                    error_log("Database Error in view_students.php: " . $e->getMessage());
                    echo "<tr><td colspan='6' style='text-align: center;'>Error fetching data from database.</td></tr>";
                }
                // $conn->close(); // Close connection only if script ends here. It's reused by search.
                ?>
            </tbody>
        </table>
    </main>

    <footer>
         <div class="footer-content">
             <img src="../frontend/ATC LOGO.png" alt="ATC Logo">
             <div class="footer-text">
                 <div class="footer-title">Arusha Technical College</div>
                 JUNCTION OF MOSHI-ARUSHA AND NAIROBI ROADS<br>
                 P.O. BOX 296, ARUSHA - TANZANIA<br>
                 TELEPHONE: +255-27-2503040/2502076, FAX: +255-27-2548337<br>
                 E-MAIL: <a href="mailto:rector@atc.ac.tz">rector@atc.ac.tz</a>
                 WEBSITE: <a href="http://www.atc.ac.tz" target="_blank">www.atc.ac.tz</a>
             </div>
             <img src="../frontend/ATC LOGO.png" alt="ATC Logo">
         </div>
    </footer>

    <script>
        document.getElementById('uploadButton').addEventListener('click', function () {
            const fileInput = document.getElementById('pdfFile');
            const progressBar = document.getElementById('progressBar');
            const progressBarFill = progressBar.querySelector('div');
            const extractedTextArea = document.getElementById('extractedText');
            const extractedTextContainer = document.getElementById('extractedTextContainer');
            const uploadButton = this; // Reference to the button

            if (!fileInput.files.length) {
                alert('Please select a PDF file to upload.');
                return;
            }

            // Disable button during upload
            uploadButton.disabled = true;
            uploadButton.textContent = 'Uploading...';

            // Reset progress bar and text area
            progressBar.style.display = 'block';
            progressBarFill.style.width = '0%';
            progressBarFill.textContent = '0%';
            extractedTextContainer.style.display = 'none'; // Hide text area initially
            extractedTextArea.value = '';


            const formData = new FormData();
            formData.append('pdfFile', fileInput.files[0]);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload_pdf.php', true);

            xhr.upload.onprogress = function (e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    progressBarFill.style.width = percentComplete + '%';
                    progressBarFill.textContent = Math.round(percentComplete) + '%';
                }
            };

            xhr.onload = function () {
                 // Re-enable button
                 uploadButton.disabled = false;
                 uploadButton.textContent = 'Upload & Process PDF';

                console.log("Raw Response:", xhr.responseText); // Log raw response
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === "success" && response.text) {
                             progressBarFill.textContent = 'Processing...';
                             extractedTextArea.value = response.text;
                             extractedTextContainer.style.display = 'block'; // Show text area

                            // --- PARSE THE TEXT ---
                            const students = parseExtractedText(response.text);
                            console.log("Students array after parsing:", students); // Log parsed students

                            if (students.length > 0) {
                                 // --- DISPLAY IN TABLE (optional - could just rely on DB insert + refresh) ---
                                 // displayStudentsInTable(students); // You might want to comment this out if insert+refresh is preferred
                                 // --- INSERT INTO DB ---
                                 insertStudentsIntoDB(students); // This function now handles success alert and refresh
                             } else {
                                 alert("Could not parse any student data from the PDF. Please check the PDF format and the console logs.");
                                 progressBar.style.display = 'none'; // Hide progress bar if no students found
                             }

                        } else {
                             alert('Error from server: ' + (response.message || 'Unknown error during PDF processing.'));
                             progressBar.style.display = 'none'; // Hide progress bar on error
                        }
                    } catch (e) {
                        console.error('Error parsing JSON response:', e);
                        console.error('Raw response that caused error:', xhr.responseText);
                        alert('An unexpected error occurred while processing the server response. Check the browser console for details.');
                         progressBar.style.display = 'none'; // Hide progress bar on error
                    }
                } else {
                    alert('HTTP error while uploading: ' + xhr.status + ' ' + xhr.statusText);
                     progressBar.style.display = 'none'; // Hide progress bar on error
                }
            };

            xhr.onerror = function () {
                 // Re-enable button
                 uploadButton.disabled = false;
                 uploadButton.textContent = 'Upload & Process PDF';
                 progressBar.style.display = 'none'; // Hide progress bar on error
                 alert('Network error occurred during upload. Please check your connection.');
            };

            xhr.send(formData);
        });

        // --- UPDATED PARSING FUNCTION ---
        function parseExtractedText(text) {
            // Normalize line endings and replace non-breaking spaces (\u00A0)
            const normalizedText = text.replace(/\u00A0/g, ' ');
            const lines = normalizedText.split('\n');
            const students = [];
            let headersProcessed = false;
            // Keywords to identify the end of the header section (adjust if needed)
            const headerEndKeywords = ["VENUE", "Program", "Exam number"]; // Finding any of these likely means headers are done or very close

            console.log("--- Starting Parse (Exam Number Prioritized Pattern Matching) ---");

            for (const line of lines) {
                const trimmedLine = line.trim();
                if (trimmedLine === '') continue; // Skip empty lines

                // --- Header Detection ---
                if (!headersProcessed) {
                     if (headerEndKeywords.some(keyword => trimmedLine.includes(keyword))) {
                         headersProcessed = true;
                         console.log("Header processing finished at line:", trimmedLine);
                     }
                     continue; // Skip header lines
                }

                // --- Data Line Processing ---
                // Skip lines that still look like headers even after flag set (safety check)
                 if (["Full name", "Admission number", "NTA level"].some(keyword => trimmedLine.includes(keyword))) {
                      console.log("Skipping likely residual header:", trimmedLine);
                      continue;
                 }

                console.log("Processing data line:", trimmedLine);

                let student = {
                   full_name: '',
                   admission_number: '',
                   nta_level: '',
                   exam_number: '',
                   program: '',
                   venue: ''
                };

                let currentString = trimmedLine; // Use currentString to track parsing progress

                // 1. Extract Full Name (first 3 words or until first digit)
                // This regex looks for 3 words at the start, followed by space and anything else
                const nameMatch = currentString.match(/^(\S+\s+\S+\s+\S+)\s+(.*)$/);
                if (nameMatch && nameMatch[1]) {
                    student.full_name = nameMatch[1];
                    currentString = nameMatch[2].trim();
                    console.log("Extracted Name:", student.full_name, "| Remaining:", currentString);
                } else {
                     // Fallback: take text until the first sequence of digits (Admission Number)
                     // This regex looks for any characters that are NOT digits at the start, followed by space and digits
                     const fallbackNameMatch = currentString.match(/^([^\d]+)\s+(\d+.*)$/);
                     if (fallbackNameMatch && fallbackNameMatch[1]) {
                          student.full_name = fallbackNameMatch[1].trim();
                          currentString = fallbackNameMatch[2].trim();
                          console.log("Extracted Name (Fallback):", student.full_name, "| Remaining:", currentString);
                     } else {
                          console.warn("Could not extract Name from line:", trimmedLine);
                          continue; // Skip if name can't be identified
                     }
                }

                // 2. Find Admission Number (sequence of digits at the start of currentString)
                const admMatch = currentString.match(/^(\d+)\s+/); // Match digits at the start, followed by space
                if (admMatch && admMatch[1]) {
                    student.admission_number = admMatch[1];
                    currentString = currentString.substring(admMatch[0].length).trim();
                    console.log("Extracted Admission Number:", student.admission_number, "| Remaining:", currentString);
                } else {
                    console.warn("Could not find Admission Number pattern in remaining string:", currentString);
                    continue; // Admission number is crucial, skip if not found
                }

                // 3. Find NTA Level (single digit, likely after Admission Number)
                const ntaMatch = currentString.match(/^(\d)\s+/); // Match a single digit at the start, followed by space
                 if (ntaMatch && ntaMatch[1]) {
                     student.nta_level = ntaMatch[1];
                     currentString = currentString.substring(ntaMatch[0].length).trim();
                     console.log("Extracted NTA Level:", student.nta_level, "| Remaining:", currentString);
                 } else {
                     console.warn("Could not find NTA Level pattern in remaining string:", currentString);
                     // NTA might be missing or in a different format, don't skip the line
                 }

                // Now, currentString contains Exam Number, Program, and Venue.
                // Let's try to find the Exam Number pattern first, as it seems more unique.
                // Exam Number pattern: T[digit]/[letters]/[digit]/[digits] or similar
                const examPattern = /(T\d+\/[A-Z]+\/\d+\/\d+)/i; // Example: T2/AE/42/7070 - Removed ^ to search anywhere

                const examMatch = currentString.match(examPattern);

                if (examMatch && examMatch[1]) {
                    student.exam_number = examMatch[1];
                    const examIndex = currentString.indexOf(examMatch[0]); // Get the actual index in currentString
                    const examLength = examMatch[0].length; // Get the full length of the matched exam string (including space before if any)

                    // Text before the exam number is part of the Program
                    let programBeforeExam = currentString.substring(0, examIndex).trim();

                    // Text after the exam number is the rest of the Program and the Venue
                    let programAndVenueString = currentString.substring(examIndex + examLength).trim();
                    console.log("Found Exam Number:", student.exam_number, "| Program Before Exam:", programBeforeExam, "| Remaining (Program + Venue):", programAndVenueString);


                    // Now, find Venue at the end of the Program + Venue string
                    // Venue pattern: short code (letters/numbers, possibly with /) at the end
                    // Modified regex: allows optional space or no space before the pattern at the end
                    const venuePattern = /\s*([A-Z0-9\/]{2,})$/i; // Added \s* to allow zero or more spaces
                    const venueMatch = programAndVenueString.match(venuePattern);

                    if (venueMatch && venueMatch[1]) {
                        student.venue = venueMatch[1];
                        // The text before the venue is the rest of the Program
                        // Need to be careful if the venue is directly attached
                        let programAfterExam = programAndVenueString.substring(0, venueMatch.index).trim();

                        // Combine program parts
                        student.program = (programBeforeExam + ' ' + programAfterExam).trim();
                        console.log("Extracted Venue:", student.venue, "| Extracted Program:", student.program);
                    } else {
                        console.warn("Could not find Venue pattern at the end of Program + Venue string:", programAndVenueString);
                        // If Venue pattern is not found at the end, the whole programAndVenueString is part of the Program
                        student.program = (programBeforeExam + ' ' + programAndVenueString).trim();
                        student.venue = ''; // Explicitly set to empty
                        console.log("Could not extract Venue. Assigning remaining to Program:", student.program);
                    }

                } else {
                    // If Exam Number pattern is not found, assume the whole remaining string is Program + Venue
                    console.warn("Could not find Exam Number pattern in remaining string:", currentString);
                    student.exam_number = ''; // Explicitly set to empty
                    let programAndVenueString = currentString; // The whole remaining string is Program + Venue

                    // Try to find Venue at the end of this string
                    const venuePattern = /\s*([A-Z0-9\/]{2,})$/i; // Allow optional space or no space
                    const venueMatch = programAndVenueString.match(venuePattern);

                    if (venueMatch && venueMatch[1]) {
                        student.venue = venueMatch[1];
                        student.program = programAndVenueString.substring(0, venueMatch.index).trim();
                        console.log("Extracted Venue (Exam Not Found):", student.venue, "| Extracted Program (Exam Not Found):", student.program);
                    } else {
                         console.warn("Could not find Venue pattern when Exam not found. Assigning whole remaining to Program.", programAndVenueString);
                         student.program = programAndVenueString;
                         student.venue = ''; // Ensure venue is empty
                         console.log("Assigned whole remaining to Program:", student.program);
                    }
                }


                // Final check and push
                // Ensure essential fields are present before pushing
                if (student.full_name && student.admission_number) {
                    console.log("Parsed Student (Final):", student);
                    students.push(student);
                } else {
                    console.warn("Skipping row due to missing essential fields (Name or Admission Number):", student, "Original line:", trimmedLine);
                }
            }

            console.log("--- Parse Complete --- Total students parsed:", students.length);
            return students;
        }


        // This function is less critical if insertStudentsIntoDB refreshes the page or table
        function displayStudentsInTable(students) {
            const resultsContainer = document.getElementById('results');
            // Clear only if you want to replace existing rows with *only* the newly parsed ones
            // resultsContainer.innerHTML = ''; // Comment this out if you want to append or let refresh handle it

            if (students.length === 0 && resultsContainer.rows.length === 0) { // Check if table is also empty
                resultsContainer.innerHTML = "<tr><td colspan='6' style='text-align: center;'>No student data parsed from PDF.</td></tr>";
                return;
            } else if (students.length === 0) {
                 // Don't clear the table if parsing failed but DB has data
                 return;
            }

            // Optional: Clear existing rows before adding new ones from PDF
            // resultsContainer.innerHTML = '';

            students.forEach(student => {
                const row = document.createElement('tr');
                // Use htmlspecialchars equivalent in JS or ensure data is safe
                const escapeHtml = (unsafe) => {
                     if (unsafe === null || typeof unsafe === 'undefined') return '';
                     return unsafe
                          .toString()
                          .replace(/&/g, "&amp;")
                          .replace(/</g, "&lt;")
                          .replace(/>/g, "&gt;")
                          .replace(/"/g, "&quot;")
                          .replace(/'/g, "&#039;");
                }
                row.innerHTML = `
                    <td>${escapeHtml(student.full_name)}</td>
                    <td>${escapeHtml(student.admission_number)}</td>
                    <td>${escapeHtml(student.nta_level)}</td>
                    <td>${escapeHtml(student.exam_number)}</td>
                    <td>${escapeHtml(student.program)}</td>
                    <td>${escapeHtml(student.venue)}</td>
                `;
                resultsContainer.appendChild(row);
            });
        }

        function insertStudentsIntoDB(students) {
             if (!students || students.length === 0) {
                 console.log("No students to insert.");
                 // Optionally hide progress bar here if needed
                 // document.getElementById('progressBar').style.display = 'none';
                 return;
             }

             console.log("Sending students to insert_students.php:", students);
             document.getElementById('progressBar').querySelector('div').textContent = 'Saving to Database...';


            fetch('insert_students.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(students) // Send the parsed student data
            })
                .then(response => {
                    if (!response.ok) {
                        // Throw an error to be caught below if response status is not 2xx
                        throw new Error(`HTTP error ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                     console.log("Response from insert_students.php:", data);
                     document.getElementById('progressBar').style.display = 'none'; // Hide progress bar
                    if (data.status === "success") {
                        alert('Student data inserted successfully! Reloading student list.');
                        location.reload(); // Reload the page to show updated list from DB
                    } else {
                        alert('Error inserting data: ' + (data.message || 'Unknown server error.'));
                    }
                })
                .catch(error => {
                     document.getElementById('progressBar').style.display = 'none'; // Hide progress bar
                    console.error('Error during fetch to insert_students.php:', error);
                    alert('An error occurred while saving data. Check the console for details. Error: ' + error.message);
                });
        }

        // Debounce function to limit search requests
         function debounce(func, wait) {
             let timeout;
             return function executedFunction(...args) {
                 const later = () => {
                     clearTimeout(timeout);
                     func(...args);
                 };
                 clearTimeout(timeout);
                 timeout = setTimeout(later, wait);
             };
         }

        // Search Functionality (using Debounce)
        const searchInput = document.getElementById('search');
        const resultsContainer = document.getElementById('results');
        const initialTableContent = resultsContainer.innerHTML; // Store initial content

         const handleSearch = debounce(function(query) {
             console.log("Searching for:", query);

             if (query === "") {
                  // Restore initial content *or* reload full list from server
                  //resultsContainer.innerHTML = initialTableContent; // Faster, but might be stale
                  fetchStudents(); // Fetch fresh list
                 return;
             }

             // Show loading state?
              resultsContainer.innerHTML = "<tr><td colspan='6' style='text-align: center;'>Searching...</td></tr>";

             // Use Fetch API for search as well for consistency
             fetch(`search_students.php?query=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error ${response.status}`);
                    }
                    return response.text(); // Get HTML response directly
                })
                .then(html => {
                    // Check if response is empty or indicates no results
                    if (!html.trim() || html.includes("No students found matching your query")) {
                         resultsContainer.innerHTML = "<tr><td colspan='6' style='text-align: center;'>No students found matching your query.</td></tr>";
                    } else {
                         resultsContainer.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                     resultsContainer.innerHTML = "<tr><td colspan='6' style='text-align: center;'>Error performing search.</td></tr>";
                });
         }, 300); // 300ms delay

        searchInput.addEventListener('input', function () {
            handleSearch(this.value.trim());
        });

        // Function to fetch all students (used for resetting search)
         function fetchStudents() {
             resultsContainer.innerHTML = "<tr><td colspan='6' style='text-align: center;'>Loading students...</td></tr>";
             fetch('fetch_all_students.php') // Create this new PHP file
                 .then(response => {
                      if (!response.ok) {
                          throw new Error(`HTTP error ${response.status}`);
                      }
                      return response.text();
                 })
                 .then(html => {
                     resultsContainer.innerHTML = html;
                 })
                 .catch(error => {
                     console.error('Error fetching all students:', error);
                     resultsContainer.innerHTML = "<tr><td colspan='6' style='text-align: center;'>Error loading student list.</td></tr>";
                 });
         }

         // --- Create fetch_all_students.php ---
         /*
         <?php
             // fetch_all_students.php
             include 'db_connection.php';
             header('Content-Type: text/html'); // Send HTML snippets

             $output = '';
             try {
                 $sql = "SELECT full_name, admission_number, nta_level, exam_number, program, venue FROM venues ORDER BY full_name";
                 $result = $conn->query($sql);

                 if ($result && $result->num_rows > 0) {
                     while ($row = $result->fetch_assoc()) {
                         $output .= "<tr>";
                         $output .= "<td>" . htmlspecialchars($row['full_name'] ?? '') . "</td>";
                         $output .= "<td>" . htmlspecialchars($row['admission_number'] ?? '') . "</td>";
                         $output .= "<td>" . htmlspecialchars($row['nta_level'] ?? '') . "</td>";
                         $output .= "<td>" . htmlspecialchars($row['exam_number'] ?? '') . "</td>";
                         $output .= "<td>" . htmlspecialchars($row['program'] ?? '') . "</td>";
                         $output .= "<td>" . htmlspecialchars($row['venue'] ?? '') . "</td>";
                         $output .= "</tr>";
                     }
                 } else {
                     $output = "<tr><td colspan='6' style='text-align: center;'>No students found in the database.</td></tr>";
                 }
             } catch (Exception $e) {
                 error_log("Database Error in fetch_all_students.php: " . $e->getMessage());
                 $output = "<tr><td colspan='6' style='text-align: center;'>Error fetching data.</td></tr>";
             }
             $conn->close();
             echo $output;
         ?>
         */

    </script>
</body>

</html>
