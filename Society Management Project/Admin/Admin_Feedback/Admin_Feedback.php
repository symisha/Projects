<?php 
require_once("../../Includes/config.php"); 
//require_once("../../Includes/session.php"); 
session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}

$query = "SELECT * FROM Feedback";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Admin_Feedback.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedbacks</title>
</head>
<body>
    <div class="admin-feedbacks-container">
        <h1>Feedbacks/Complaints</h1>
        <table>
            <thead>
                <tr>
                    <th>Resident ID</th>
                    <th>Feedback Type</th>
                    <th>Feedback Text</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row["Resident_ID"]; ?></td>
                        <td><?php echo $row["Feedback_Type"]; ?></td>
                        <td><?php echo $row["Feedback_Text"]; ?></td>
                        <td><?php echo $row["Created_At"]; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>