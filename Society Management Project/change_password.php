<?php
require_once("Includes/config.php"); //equire_once("Includes/session.php"); 
session_start();
// Check if the user is logged in and prevent reloading
if ($_SESSION['logged'] != true && !isset($_SESSION['pageLoaded'])) {
    $_SESSION['pageLoaded'] = false; // Flag to track page load status
    echo 'You must be logged in to change your password.';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['pageLoaded'] = true;
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate input
    if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
        echo 'All fields are required.';
        exit();
    }
    if ($new_password !== $confirm_password) {
        echo 'New password and confirm password do not match.';
        exit();
    }

    // Determine user type
    if (isset($_SESSION['Resident_ID'])) {
        $user_id = $_SESSION['Resident_ID'];
        $table = 'residents';
        $id_column = 'Resident_ID';
    } elseif (isset($_SESSION['Employee_ID'])) {
        $user_id = $_SESSION['Employee_ID'];
        $table = 'Employees';
        $id_column = 'Employee_ID';
    } else {
        echo 'Error: No user ID found in session.';
        exit();
    }

    // Check old password
    $query = "SELECT Password FROM $table WHERE $id_column = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify the old password
        if ($old_password === $row['Password']) {
            // Update the password in the database
            $update_query = "UPDATE $table SET Password = ? WHERE $id_column = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, 'si', $new_password, $user_id);

            if (mysqli_stmt_execute($update_stmt)) {
                echo 'Password changed successfully.';
            } else {
                echo 'Error updating password. Please try again.';
            }
        } else {
            echo 'Old password is incorrect.';
        }
    } else {
        echo 'User not found.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="change_password.css">

    <style>
        .alert {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <h3>Change Password</h3>

    <!-- Change Password Form -->
    <form id="passwordForm" method="POST">
        <label for="old_password">Old Password:</label>
        <input type="password" id="old_password" name="old_password" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit">Change Password</button>
    </form>

    <!-- Response Message -->
    <div id="responseMessage"></div>

    <!-- JavaScript for AJAX -->
    <script>
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(this); // Collect the form data

            fetch('../../change_password.php', {  // URL of the PHP script
                method: 'POST', 
                body: formData, // Send form data
            })
            .then(response => response.text())  // Convert response to text
            .then(data => {
                // Handle the response
                const messageContainer = document.getElementById('responseMessage');

                if (data.includes("Password changed successfully")) {
                    messageContainer.innerHTML = '<div class="alert alert-success">Password changed successfully.</div>';
                    document.getElementById('passwordForm').reset(); // Clear form on success
                } else if (data.includes("Old password is incorrect")) {
                    messageContainer.innerHTML = '<div class="alert alert-danger">Old password is incorrect.</div>';
                } else {
                    messageContainer.innerHTML = '<div class="alert alert-danger">An error occurred. Please try again.</div>';
                }
            })
            .catch(error => console.error('Error:', error));  // Log any errors
        });
    </script>
</body>
</html>
