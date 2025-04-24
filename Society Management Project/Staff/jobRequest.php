<?php
// Include config and session files
require_once ("../Includes/config.php");


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_SESSION['Resident_ID'];
    $job_name = $_POST['Job_Name'];
    $description = $_POST['description'];

    // Retrieve job_id based on job_name
    $sql = "SELECT Job_ID FROM Job WHERE Job_Name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $job_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $job_id = $row['Job_ID'];

        // Insert job request using house_ID for the resident
        $sql = "INSERT INTO Job_Requests (Job_ID, Resident_ID, house_ID, status, request_date, description)
                SELECT ?, ?, r.house_ID, 'pending', NOW(), ?
                FROM residents r
                JOIN Houses h ON r.House_ID = h.house_id
                WHERE r.resident_id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $job_id, $resident_id, $description, $resident_id);

        if ($stmt->execute()) {
            $request_id = $stmt->insert_id;
            echo "Job request created successfully with Request ID: " . $request_id;

            // Call function to assign an available employee
            assignEmployeeToRequest($conn, $job_id, $request_id);
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Job type not found.";
    }

    $stmt->close();
    $conn->close();
}

// Function to assign an available employee to the job request
function assignEmployeeToRequest($conn, $job_id, $request_id) {
    $sql = "SELECT Staff_ID FROM Staff 
    WHERE Job_ID = ? 
    AND Availability = 'available' 
    ORDER BY last_assigned ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $staff_result = $stmt->get_result();

    if ($staff_result->num_rows > 0) {
        $staff = $staff_result->fetch_assoc();
        $staff_id = $staff['Staff_ID'];

        // Update job request with the assigned employee
        $update_sql = "UPDATE Job_Requests SET assigned_employee_id = ? WHERE request_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $staff_id, $request_id);
        $update_stmt->execute();

        // Update employee availability or last assigned time
        $availability_sql = "UPDATE Staff SET Availability = 'not available', Last_Assigned = NOW() WHERE Staff_ID = ?";
        $availability_stmt = $conn->prepare($availability_sql);
        $availability_stmt->bind_param("i", $staff_id);
        $availability_stmt->execute();
    } else {
        echo "No available employee found for this job type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Request</title>
    <link rel="stylesheet" href="jobReq.css">
</head>
<body>
    <div class="container">
        <h1>Request a Service</h1>
        <form id="job-request-form" method="POST" action="jobRequest.php">
            <label for="service-type">Select Service:</label>
            <select id="service-type" name="Job_Name" required>
                <option value="" disabled selected>Select a service</option>
                <option value="Maid">Maid</option>
                <option value="Gardener">Gardener</option>
                <option value="BabySitter">Baby Sitter</option>
                <option value="Care Taker">Care Taker</option>
                <option value="Guard">Guard</option>
            </select>
            <label for="request-details">Details (optional):</label>
            <textarea id="request-details" name="description" rows="4" placeholder="Describe your issue here..."></textarea>

            <button type="submit">Submit Request</button>
        </form>
    </div>
</body>
</html>
