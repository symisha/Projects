<?php
require_once("../../Includes/config.php");
require_once("../../admin/SendEmail.php");

// Your existing code...
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['status'])) {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];

    // Update the status to 'completed' in the Job_Requests table
    if ($status === 'completed') {
        $sql = "UPDATE Job_Requests SET status = ? WHERE request_id = ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $request_id);

        if ($stmt->execute()) {
            // Fetch resident email, first name, job details, and staff ID
            $query = "
                SELECT r.Email, r.First_Name, j.Job_Name, jr.assigned_employee_id 
                FROM Job_Requests jr
                JOIN residents r ON jr.Resident_ID = r.Resident_ID
                JOIN Job j ON jr.Job_ID = j.Job_ID
                WHERE jr.request_id = ?";
            
            $fetch_stmt = $conn->prepare($query);
            $fetch_stmt->bind_param("i", $request_id);
            $fetch_stmt->execute();
            $result = $fetch_stmt->get_result();
        
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $resident_email = $row['Email'];
                $job_name = $row['Job_Name'];
                $first_name = $row['First_Name']; // Optional for personalized email
                $staff_id = $row['assigned_employee_id']; // Get the staff ID
                
                // Prepare the email content
                $subject = "Job Request Completed";
                $body = "
                    <p>Dear $first_name,</p>
                    <p>Your job request for <b>$job_name</b> has been marked as <b>completed</b>.</p>
                    <p>Please confirm whether the task was completed satisfactorily.</p>
                    <p>Thank you for using our Society Management System.</p>
                ";

                // Send the email
                sendEmail($resident_email, $subject, $body, $resident_id, $first_name);

                // Update staff availability
                $updateStaffSql = "UPDATE Staff SET Availability = 'available' WHERE Staff_ID = ?";
                $updateStmt = $conn->prepare($updateStaffSql);
                $updateStmt->bind_param("i", $staff_id);
                $updateStmt->execute();
                $updateStmt->close();
            }

            // Redirect back to the employee requests page after update
            header("Location: EmployeesRequest.php");
            exit();
        } else {
            echo "Error updating the status.";
        }

        $stmt->close();
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>