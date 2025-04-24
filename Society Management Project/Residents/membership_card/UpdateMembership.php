<?php
session_start();
require_once("../../Includes/config.php");
require_once("../../Admin/SendEmail.php");
// Ensure the user is logged in and has a valid session
if (!isset($_SESSION['Resident_ID'])) {
    header("Location: login.php");
    exit();
}

$resident_id = $_SESSION['Resident_ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $member_id = $_POST['member_id'];
    $current_card_id = $_POST['current_card_id'];
    $new_card_id = $_POST['new_card_id'];

    // Begin transaction to update membership details
    $conn->begin_transaction();

    try {
        // Step 1: Update the Membership Table with the new card ID
        $update_query = "UPDATE Memberships SET Card_ID = ? WHERE Member_ID = ? AND Resident_ID = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("iii", $new_card_id, $member_id, $resident_id);
        if (!$update_stmt->execute()) {
            throw new Exception("Failed to update membership card.");
        }

        // Step 2: Fetch the new card details for confirmation
        $card_query = "SELECT Card_Name, Fee FROM MembershipCards WHERE Card_ID = ?";
        $card_stmt = $conn->prepare($card_query);
        $card_stmt->bind_param("i", $new_card_id);
        $card_stmt->execute();
        $card_result = $card_stmt->get_result();
        $card_details = $card_result->fetch_assoc();

        
        $query = "SELECT Email FROM Residents WHERE Resident_ID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $resident_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            // Fetch the email
            $resident = $result->fetch_assoc();
            $email = $resident['Email'];
        }
        $stmt->close();

        $subject = "Membership Card Update Confirmation";
        $message = "Your membership card has been updated to '{$card_details['Card_Name']}' with a fee of {$card_details['Fee']} PKR.";
        SendMail($email, $subject, $message, null, null, null);

        // Step 4: Commit transaction
        $conn->commit();

        // Redirect to a success page or show success message
        echo "<script>alert('Your membership has been successfully updated to {$card_details['Card_Name']}'); window.location.href='membership_management.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='membership_management.php';</script>";
    }
}
?>

