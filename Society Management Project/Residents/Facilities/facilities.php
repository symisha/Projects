<?php 
require_once("../../Includes/config.php"); 
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['Resident_ID']) || $_SESSION['Resident_ID'] != true || $_SESSION["logged"] != true) {
    header("Location: ../login - Admin.php?noiframe=true");
    exit;
}

// Fetch facilities grouped by Facility_Name and Max_Capacity
$query = "
    SELECT 
        Facility_Name, 
        MAX(Description) AS Description, 
        Max_Capacity, 
        GROUP_CONCAT(DISTINCT Membership_Type ORDER BY Membership_Type ASC SEPARATOR ', ') AS Membership_Types 
    FROM Facilities 
    GROUP BY Facility_Name, Max_Capacity
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="facilities.css">
    <title>Facilities</title>
    <style>
        /* Membership Type Colors */
        .diamond { color: #d5b710; font-weight: bold; } /* Gold-like color */
        .golden { color: #ffa413; font-weight: bold; }  /* Deep golden color */
        .silver { color: #7eb5fe; font-weight: bold; }  /* Silver color */
    </style>
</head>
<body>
    <div class="container">
        <h1>Facilities</h1>
        <table>
            <thead>
                <tr>
                    <th>Facility</th>
                    <th>Details</th>
                    <th>Capacity</th>
                    <th>Membership Types</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['Facility_Name']); ?></td>
                    <td><?php echo htmlspecialchars($row['Description']); ?></td>
                    <td><?php echo htmlspecialchars($row['Max_Capacity']); ?></td>
                    <td>
                        <?php 
                        // Split membership types and add colors
                        $types = explode(', ', $row['Membership_Types']);
                        foreach ($types as $type) {
                            $class = strtolower($type); // Convert membership type to lowercase for class name
                            echo "<span class='{$class}'>" . ucfirst($type) . "</span> ";
                        }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
