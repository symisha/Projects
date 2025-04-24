<?php
// Receive the QR code data
$data = json_decode(file_get_contents('php://input'), true);
$qrData = $data['qr_data'];

// Process the data (e.g., store in database, etc.)
$response = array('message' => 'QR Code Processed Successfully: ' . $qrData);
echo json_encode($response);
?>
