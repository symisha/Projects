<?php
// Include your database connection
require_once 'C:/xampp/htdocs/GitHub/DB/Project/Includes/config.php';

$Resident_ID = 1000071; // Example Resident_ID, replace as needed
$plainPassword = 'miso123'; // The plain password

// Hash the password
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Update the password in the database
$query = "UPDATE residents SET Password = '$hashedPassword' WHERE Resident_ID = $Resident_ID";
if (mysqli_query($conn, $query)) {
    echo "Password updated successfully!";
} else {
    echo "Error updating password: " . mysqli_error($conn);
}
?>
