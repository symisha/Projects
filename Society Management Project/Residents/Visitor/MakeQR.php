<?php
session_start(); // Start the session

include ('../../phpqrcode-master/qrlib.php');
include ('../../Admin/SendEmail.php');

$Resident_ID = $_SESSION['Resident_ID']; // Placeholder for Resident_ID

function MakeQR($ID, $email, $qr_data, $qr_file, $subject, $message)
{
// Generate and save the QR code
QRcode::png($qr_data, $qr_file);
// Send the email with the QR code attached
sendEmail($email, $subject, $message, null, null, $qr_file);
// Optionally, delete the QR code file after sending the email to clean up
//unlink($qr_file);
}
?>
