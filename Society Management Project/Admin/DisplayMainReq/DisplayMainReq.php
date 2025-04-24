<?php 
require_once("../../Includes/config.php"); 
//require_once("../../Includes/session.php"); 
session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}

$query = "SELECT * FROM Maintenance_Requests";
$result = mysqli_query($conn, $query);

$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestID = $_POST["requestID"];
    $status = $_POST["status"];

    // Update maintenance request status
    $query = "UPDATE Maintenance_Requests SET Status = '$status' WHERE Request_ID = '$requestID'";
    if (mysqli_query($conn, $query)) {
        // If status is 'Done', add billing entry
        // If status is 'Done', add billing entry and delete maintenance request
            if ($status == 'Done') {
                $query = "SELECT * FROM Maintenance_Requests WHERE Request_ID = '$requestID'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $residentID = $row["Resident_ID"];
                $price = $row["Price"];
                $billingMonth = date("M");
                $dueDate = date("Y-m-d", strtotime("+30 days"));

                // Add billing entry
                $query = "INSERT INTO Billing (Resident_ID, Billing_Amount, Due_Date, Status, Billing_Month, Service_Type) 
                        VALUES ('$residentID', '$price', '$dueDate', 'Pending', '$billingMonth', 'Maintenance')";
                if (mysqli_query($conn, $query)) {
                    // Delete maintenance request
                    $query = "DELETE FROM Maintenance_Requests WHERE Request_ID = '$requestID'";
                    if (mysqli_query($conn, $query)) {
                        $errorMessage = "Maintenance request updated, billing added, and request deleted successfully!";
                    } else {
                        $errorMessage = "Error deleting maintenance request: " . mysqli_error($conn);
                    }
                } else {
                    $errorMessage = "Error adding billing: " . mysqli_error($conn);
                }
            }else {
            $errorMessage = "Maintenance request updated successfully!";
        }
    } else {
        $errorMessage = "Error updating maintenance request: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="DisplayMainReq.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Maintenance Requests</h1>
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Resident ID</th>
                    <th>Request Type</th>
                    <th>Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row["Request_ID"]; ?></td>
                    <td><?php echo $row["Resident_ID"]; ?></td>
                    <td><?php echo $row["Request_Type"]; ?></td>
                    <td><?php echo $row["Description"]; ?></td>
                    <td><?php echo $row["Price"]; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <p style="color:<?php echo strpos($errorMessage, 'Error') !== false ? 'red' : 'green'; ?>"><?php echo $errorMessage; ?></p>
    </div>
</body>
</html>