<?php
require_once("../Includes/config.php");

session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}

// Query to get all houses with details (including Owner)
$sql = "SELECT h.House_ID, h.Street, h.Sector, rho.Resident_ID AS Owner_ID
        FROM Houses h
        LEFT JOIN Resident_House_Ownership rho ON h.House_ID = rho.House_ID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa; /* Light grey background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Container Styles */
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 1000px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            color: #05555c;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .table-container {
            margin-top: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #38b6a5; /* Dark teal */
            color: #fff;
            font-weight: bold;
        }

        .rented {
            color: red;
            font-weight: bold;
        }

        .not-rented {
            color: green;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1; /* Light grey on hover */
        }

        /* Error/Success Message Styles */
        p {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        p.error {
            color: red;
        }

        p.success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>House Details</h1>
            <p>Below is the list of all houses in the society, including their ownership, rental status, and respective residents.</p>
        </div>

        <!-- Display the houses table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>House ID</th>
                        <th>Street</th>
                        <th>Sector</th>
                        <th>Owner (Resident ID)</th>
                        <th>Rental Resident (Resident ID)</th>
                        <th>Rental Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Loop through each row and display house details
                        while ($row = $result->fetch_assoc()) {
                            $house_id = $row['House_ID'];
                            $street = $row['Street'];
                            $sector = $row['Sector'];
                            $owner_id = $row['Owner_ID'];

                            // Check if the house has been rented by looking in the Resident_House_Rent table
                            $rental_sql = "SELECT Resident_ID FROM Resident_House_Rent WHERE House_ID = ?";
                            $stmt = $conn->prepare($rental_sql);
                            $stmt->bind_param("s", $house_id);
                            $stmt->execute();
                            $rental_result = $stmt->get_result();

                            // Determine rental status
                            if ($rental_result->num_rows > 0) {
                                $rental_resident = $rental_result->fetch_assoc()['Resident_ID'];
                                $rental_status = "<span class='rented'>Rented</span>";
                            } else {
                                $rental_resident = "N/A";
                                $rental_status = "<span class='not-rented'>Not Rented</span>";
                            }
                            $stmt->close();

                            // Display the house details in the table
                            echo "<tr>
                                    <td>$house_id</td>
                                    <td>$street</td>
                                    <td>$sector</td>
                                    <td>" . ($owner_id ? $owner_id : "N/A") . "</td>
                                    <td>" . ($rental_resident ? $rental_resident : "N/A") . "</td>
                                    <td>$rental_status</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No houses found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
