<?php
session_start();
require_once("../../Includes/config.php");
require_once("../../Residents/Visitor/MakeQR.php");
// Ensure the database connection is valid
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Retrieve GET parameters and sanitize them
$num = isset($_GET['num']) ? (int)$_GET['num'] : null;
$resident_id = isset($_GET['resident_id']) ? (int)$_GET['resident_id'] : null;

$errorMessage = '';
$bill_id = ''; // Ensure this is initialized properly if used later

// Check if the request is a POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $_POST;
    $transactionStatus = $response['pp_ResponseCode'] ?? '';
    $message = $response['pp_ResponseMessage'] ?? 'Transaction status unavailable.';

    echo '<div class="status-card">';

    // Transaction status handling
    if ($transactionStatus === '000') {
        echo '<div class="status-icon error-icon">❌</div>';
        echo '<h1 class="error">Transaction Failed!</h1>';
        echo '<p>' . htmlspecialchars($message) . '</p>';
    } else {
        echo '<div class="status-icon success-icon">✔️</div>';
        echo '<h1 class="success">Transaction Successful!</h1>';
        echo '<p>Congratulations! Your transaction was successful.</p>';

        try {
            if ($num === 1) {
                // Update Billing Status
                $stmt = $conn->prepare("UPDATE Billing SET Status = 'Paid' WHERE Billing_ID = ?");
                if (!$stmt) {
                    throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("i", $bill_id);
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
                echo '<p>Billing status updated successfully.</p>';
            } elseif ($num === 2) {
                $cardID = isset($_GET['cardID']) ? (int)$_GET['cardID'] : null;
                // Start a transaction for Membership and Billing
                $conn->begin_transaction();
                $date = date("Y-m-d");

                // Insert into Memberships
                $query = "INSERT INTO Memberships (Resident_ID, Card_ID, Status, Subscription_Date) VALUES (?, ?, 'Paid', ?)";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    throw new Exception("Prepare failed for Memberships: " . $conn->error);
                }
                $stmt->bind_param("iis", $resident_id, $cardID, $date);
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed for Memberships: " . $stmt->error);
                }
                $memberID = $stmt->insert_id;

                // Insert into Membership_Bills
                $due = date("Y-m-d", strtotime("+15 days"));
                $billingQuery = "INSERT INTO Membership_Bills (Member_ID, Card_ID, Due_Date, Payment_Date, Status) VALUES (?, ?, ?, ?, 'Paid')";
                $billingStmt = $conn->prepare($billingQuery);
                if (!$billingStmt) {
                    throw new Exception("Prepare failed for Membership_Bills: " . $conn->error);
                }
                $billingStmt->bind_param("iiss", $memberID, $cardID, $due, $date);
                if (!$billingStmt->execute()) {
                    throw new Exception("Execute failed for Membership_Bills: " . $billingStmt->error);
                }

                // Commit transaction
                $conn->commit();

                $query = "SELECT Email FROM Residents WHERE Resident_ID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $resident_id);
                $stmt->execute();
                $result = $stmt->get_result();
            
                if ($result->num_rows > 0) {
                    // Fetch the email
                    $resident = $result->fetch_assoc();
                    $email = $resident['Email'];
                
                $qr_data = 'http://localhost/Project1/DB/Project/Residents/Visitor/verify.php?Entry_ID=' . $memberID;
                $qr_file = 'membership_qr_' . $memberID . '.png';
                $subject = "Membership QR";
                $message = "Welcome Member. Use this QR to make entries and exits from the facility.";
                MakeQR($memberID, $email, $qr_data, $qr_file, $subject, $message);
                }
                $stmt->close();
                echo '<p>Membership and billing details successfully recorded.</p>';
            }
            else if($num == 3)
            {
                // Update Billing Status
                $stmt = $conn->prepare("UPDATE membership_bills SET Status = 'Paid' WHERE Billing_ID = ?");
                if (!$stmt) {
                     throw new Exception("Prepare failed: " . $conn->error);
                }
                $stmt->bind_param("i", $bill_id);
                if (!$stmt->execute()) {
                    throw new Exception("Execute failed: " . $stmt->error);
                }            }
        } catch (Exception $e) {
            // Rollback transaction and display error
            echo '<p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }
    echo '</div>';
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .status-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        .status-card h1 {
            margin: 0;
            font-size: 24px;
        }
        .status-card p {
            font-size: 16px;
            margin: 10px 0 0;
        }
        .success {
            color: #4caf50;
        }
        .error {
            color: #f44336;
        }
        .status-icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
        .success-icon {
            color: #4caf50;
        }
        .error-icon {
            color: #f44336;
        }
    </style>
</head>
<body>
    <div class="status-card">
        <!-- Content dynamically added via PHP -->
    </div>
</body>
</html>
