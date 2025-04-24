<?php
session_start();
require_once ("../../Includes/config.php");
//require_once 'C:/xampp/htdocs/GitHub/DB/Project/Includes/session.php';
// session_start();
// if(isset($_SESSION['Resident']))
// if ($_SESSION['Resident'] != true || $_SESSION["logged"] != true) {
//   header("Location: ../login - Resident.php?noiframe=true");
// }

/*
if(!isset($_SESSION['Employee_ID']))
{
    exit;
}
*/
$employee_id = $_SESSION['Employee_ID'];

// Fetch job requests assigned to this employee
$sql = "SELECT jr.request_id, jr.description, j.Job_Name, jr.status, jr.request_date
        FROM job_requests jr
        JOIN job j ON jr.Job_ID = j.Job_ID
        WHERE jr.assigned_employee_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Job Requests</title>
    <link rel="stylesheet" href="EmployeesRequest.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <h1>Assigned Job Requests</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Job Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['request_id']; ?></td>
                            <td><?php echo $row['Job_Name']; ?></td>
                            <td><?php echo $row['description']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['request_date']; ?></td>
                            <td>
                                <?php if ($row['status'] == 'pending'): ?>
                                    <form action="update_request.php" method="post">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                        <button type="submit" name="status" value="completed">Mark as Completed</button>
                                    </form>
                                <?php else: ?>
                                    Completed
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No job requests assigned to you at this time.</p>
        <?php endif; ?>

        <?php $stmt->close(); ?>
    </div>
</body>
</html>