<?php
// Include necessary files
require_once("../Includes/config.php");

// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy the session
session_destroy();

// Redirect to the home page
header("location: login - Resident.php");

exit();
?>
