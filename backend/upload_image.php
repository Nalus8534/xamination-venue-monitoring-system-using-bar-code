<?php
// upload_image.php
include 'session_check.php'; // Ensure the user is logged in
include 'db_connection.php'; // Include database connection

$message = ""; // Initialize message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data and confirmation flag
    $admission_number = trim($_POST['admission_number'] ?? '');
    $replace_existing = isset($_POST['replace_existing']) && $_POST['replace_existing'] === 'yes';

    // Check if an admission number was provided
    if (empty($admission_number)) {
        $message = "<div style='color: red;'>Error: Admission number is required.</div>";
    }
    // Check if a file was uploaded without errors
    else if (!isset($_FILES['studentImage']) || $_FILES['studentImage']['error'] !== UPLOAD_ERR_OK) {
        // Only show specific file upload errors if a file was intended to be uploaded
        if ($_FILES['studentImage']['error'] !== UPLOAD_ERR_NO_FILE) {
             $message = "<div style='color: red;'>Error: No file uploaded or upload error.</div>";
             switch ($_FILES['studentImage']['error']) {
                 case UPLOAD_ERR_INI_SIZE:
                 case UPLOAD_ERR_FORM_SIZE:
                     $message .= " File is too large.";
                     break;
                 case UPLOAD_ERR_PARTIAL:
                     $message .= " File upload was partial.";
                     break;
                 case UPLOAD_ERR_NO_TMP_DIR:
                     $message .= " Missing temporary folder.";
                     break;
                 case UPLOAD_ERR_CANT_WRITE:
                     $message .= " Failed to write file to disk.";
                     break;
                 case UPLOAD_ERR_EXTENSION:
                     $message .= " A PHP extension stopped the file upload.";
                     break;
                 default:
                     $message .= " Unknown upload error.";
                     break;
             }
        } else {
             // This case should ideally be caught by the 'required' attribute on the file input,
             // but adding a server-side check is good practice.
             $message = "<div style='color: red;'>Error: No file was selected for upload.</div>";
        }

    } else {
        $file = $_FILES['studentImage'];

        // Validate file type (allow common image types)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $detected_type = finfo_file($file_info, $file['tmp_name']);
        finfo_close($file_info);

        if (!in_array($detected_type, $allowed_types)) {
            $message = "<div style='color: red;'>Error: Invalid file type. Only JPG, PNG, and GIF are allowed.</div>";
        }
        // Validate file size (e.g., max 5MB)
        else if ($file['size'] > 5 * 1024 * 1024) { // 5MB
            $message = "<div style='color: red;'>Error: File size exceeds the limit (5MB).</div>";
        }
        else {
            // Define the target directory for uploads
            $target_directory = "../uploads/student_images/";
            // Ensure the directory exists and is writable
            if (!is_dir($target_directory)) {
                // Attempt to create the directory
                if (!mkdir($target_directory, 0755, true)) { // Use 0755 for better security
                     $message = "<div style='color: red;'>Error: Failed to create upload directory. Check server permissions.</div>";
                }
            }
             // Re-check if directory exists and is writable after attempted creation
             if (!is_dir($target_directory) || !is_writable($target_directory)) {
                 $message = "<div style='color: red;'>Error: Upload directory is not writable or does not exist.</div>";
             } else {
                // Check if an image already exists for this student
                $check_sql = "SELECT image_path FROM venues WHERE admission_number = ? LIMIT 1";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param("s", $admission_number);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                $existing_image_row = $check_result->fetch_assoc();
                $check_stmt->close();

                $image_exists = $existing_image_row && !empty($existing_image_row['image_path']);
                $existing_path = $image_exists ? $existing_image_row['image_path'] : null;

                // --- Handle Replace Logic ---
                if ($image_exists && !$replace_existing) {
                    // If image exists and user hasn't confirmed replacement via the hidden field
                    // This scenario should ideally be handled by the JS confirmation,
                    // but this is a server-side fallback/validation.
                     $message = "<div style='color: red;'>An image already exists for this student. Please confirm if you want to replace it.</div>";
                     // Note: The JS handles the confirmation dialog before submitting with replace_existing=yes
                } else {
                    // If no image exists, OR if image exists AND replace_existing is 'yes'
                    // Proceed with saving the new image

                    // If replacing an existing image, delete the old file first
                    if ($image_exists && $replace_existing && $existing_path && file_exists($existing_path)) {
                        if (unlink($existing_path)) {
                            // Old file deleted successfully
                            error_log("Deleted old image file: " . $existing_path); // Log deletion
                        } else {
                            // Failed to delete old file (might be a permission issue or file in use)
                            error_log("Failed to delete old image file: " . $existing_path); // Log failure
                            // Decide if you want to stop the upload or proceed.
                            // For now, we'll proceed but log the error.
                        }
                    }


                    // Generate a unique filename based on admission number and original extension
                    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    // Sanitize admission number for use in filename
                    $safe_admission_number = preg_replace('/[^a-zA-Z0-9_-]/', '', $admission_number);
                    // Add a timestamp or unique ID to the filename to prevent browser caching issues
                    $new_file_name = $safe_admission_number . '_' . time() . '.' . strtolower($file_extension); // Using timestamp
                    // $new_file_name = $safe_admission_number . '_' . uniqid() . '.' . strtolower($file_extension); // Using uniqid
                    $target_file = $target_directory . $new_file_name;

                    // Move the uploaded file
                    if (move_uploaded_file($file['tmp_name'], $target_file)) {
                        // File uploaded successfully, now update the database

                        // Store the web-accessible path in the database
                        // The path should be relative to the document root or a known web path
                        // Assuming 'uploads' is directly under your web root (htdocs)
                        $image_path_for_db = "../uploads/student_images/" . $new_file_name;

                        // Prepare and execute SQL query to update the student's image_path
                        // We assume admission_number is unique and exists in the 'venues' table
                        $sql = "UPDATE venues SET image_path = ? WHERE admission_number = ?";
                        $stmt = $conn->prepare($sql);

                        if ($stmt) {
                            $stmt->bind_param("ss", $image_path_for_db, $admission_number);

                            if ($stmt->execute()) {
                                $message = "<div style='color: green;'>Image uploaded and linked successfully for Admission Number: " . htmlspecialchars($admission_number) . "</div>";
                            } else {
                                $message = "<div style='color: red;'>Error updating database: " . $stmt->error . "</div>";
                                // Optional: Delete the uploaded file if database update fails
                                // unlink($target_file);
                            }
                            $stmt->close();
                        } else {
                             $message = "<div style='color: red;'>Database error preparing statement: " . $conn->error . "</div>";
                             // Optional: Delete the uploaded file if statement preparation fails
                             // unlink($target_file);
                        }

                    } else {
                        $message = "<div style='color: red;'>Error moving uploaded file. Check server permissions.</div>";
                    }
                 }
             }
        }
    }

    // Close the database connection
    // $conn->close(); // Keep connection open if needed for displaying the form again
}
// Close the database connection if it was opened and not closed in the POST block
if (isset($conn) && $conn) {
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Student Image</title>
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
            display: flex;
            justify-content: center; /* Center form horizontally */
            align-items: flex-start; /* Align items to the top */
            padding: 20px;
            width: 100%; /* Take full width */
            box-sizing: border-box; /* Include padding in width */
        }

        form {
            max-width: 500px;
            width: 100%; /* Make form responsive */
            padding: 30px;
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

        form input[type="text"],
        form input[type="file"],
        form button {
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

         form input[type="file"] {
             background-color: #2c2c3e; /* Match container background */
             cursor: pointer;
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
        .message { /* Using a class instead of attribute selector for consistency */
            margin-top: -10px; /* Adjust margin to be closer to the button */
            margin-bottom: 20px; /* Space below the message */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .message.success { /* Class for success messages */
            background-color: #28a745; /* Green background for success */
            color: white; /* Ensure text is white */
        }

        .message.error { /* Class for error messages */
            background-color: #dc3545; /* Red background for error */
            color: white; /* Ensure text is white */
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
        <h1>Upload Student Image</h1>
    </div>
    <main>
        <form id="uploadImageForm" action="upload_image.php" method="POST" enctype="multipart/form-data">
            <label for="admission_number">Student Admission Number:</label>
            <input type="text" id="admission_number" name="admission_number" placeholder="Enter student admission number" required>

            <label for="studentImage">Select Student Image:</label>
            <input type="file" id="studentImage" name="studentImage" accept="image/*" required>

            <input type="hidden" name="replace_existing" id="replaceExisting" value="no">

            <button type="submit" id="submitButton">Upload Image</button>

            <?php if (!empty($message)): ?>
                <?php
                    // Determine message class based on content
                    $message_class = (strpos($message, 'Error:') !== false) ? 'error' : 'success';
                    // Remove inline style for color and use class instead
                    $cleaned_message = str_replace(["style='color: red;'", "style='color: green;'", "margin-top: 10px;"], "", $message);
                ?>
                <div class="message <?php echo $message_class; ?>">
                    <?php echo $cleaned_message; ?>
                </div>
            <?php endif; ?>

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

    <script>
        const uploadForm = document.getElementById('uploadImageForm');
        const admissionNumberInput = document.getElementById('admission_number');
        const studentImageInput = document.getElementById('studentImage');
        const replaceExistingInput = document.getElementById('replaceExisting');
        const submitButton = document.getElementById('submitButton');

        // Prevent default form submission initially
        uploadForm.addEventListener('submit', function(event) {
            // The form submission will be triggered by the JavaScript after check/confirmation
            event.preventDefault();
        });

        // Listen for changes on the file input
        studentImageInput.addEventListener('change', function() {
            const admissionNumber = admissionNumberInput.value.trim();
            const file = this.files[0];

            // Reset confirmation value
            replaceExistingInput.value = 'no';

            if (!admissionNumber) {
                alert("Please enter the student's Admission Number first.");
                // Clear the file input if admission number is missing
                studentImageInput.value = '';
                return;
            }

            if (file) {
                // Check if an image already exists for this admission number
                checkExistingImage(admissionNumber, file);
            }
        });

        // Function to check for existing image
        function checkExistingImage(admissionNumber, file) {
            // Disable the submit button while checking
            submitButton.disabled = true;
            submitButton.textContent = 'Checking...';

            fetch(`check_image.php?admission_number=${encodeURIComponent(admissionNumber)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    submitButton.disabled = false; // Re-enable button
                    submitButton.textContent = 'Upload Image';

                    if (data.exists) {
                        // Image exists, ask for confirmation to replace
                        const confirmReplace = confirm(`An image already exists for Admission Number ${admissionNumber}. Do you want to replace it?`);

                        if (confirmReplace) {
                            // User confirmed replacement, set the hidden field and submit
                            replaceExistingInput.value = 'yes';
                            // Trigger the form submission programmatically
                            uploadForm.submit();
                        } else {
                            // User cancelled, clear the file input
                            studentImageInput.value = '';
                            alert("Image upload cancelled.");
                        }
                    } else {
                        // No image exists, proceed with upload
                        // Trigger the form submission programmatically
                        uploadForm.submit();
                    }
                })
                .catch(error => {
                    submitButton.disabled = false; // Re-enable button on error
                    submitButton.textContent = 'Upload Image';
                    console.error('Error checking for existing image:', error);
                    alert('An error occurred while checking for an existing image. Please try again.');
                    // Clear the file input on error
                    studentImageInput.value = '';
                });
        }

        // Optional: Add validation on manual submit button click as well
        // submitButton.addEventListener('click', function(event) {
        //     // If the file input has a file selected, the change event will handle the check/submit
        //     // If no file is selected, the 'required' attribute on the input will handle it.
        //     // This listener is mainly for redundancy or if you change the flow.
        // });


    </script>
</body>
</html>
