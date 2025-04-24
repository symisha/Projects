<?php
require_once("../Includes/config.php");

// Generate bills for all residents

$sql = "INSERT INTO Billing (Resident_ID, Billing_Amount, Due_Date, Billing_Month)
SELECT Resident_ID, 7000, DATE_ADD(CURDATE(), INTERVAL 30 DAY), MONTH(CURDATE())
FROM Residents;";

if ($conn->query($sql) === TRUE) {
    $rowsInserted = $conn->affected_rows;
    echo "$rowsInserted billing records generated successfully.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close(); 
?>