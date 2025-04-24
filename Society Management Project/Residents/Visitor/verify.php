<?php

require_once('../../Includes/config.php');
session_start();

// Check if 'Entry_ID' is provided in the session or as a GET parameter
if (!isset($_SESSION['Entry_ID'])) {
    $Entry_ID = $_GET['Entry_ID'];  // Set a default value if not found in session (or get it via $_GET if necessary)

    // Prepare the SQL query to fetch details
    $stmt = $conn->prepare("
        SELECT 
            v.Entry_ID, v.Entry_Date, v.Purpose_of_Visit, 
            r.Resident_ID, r.First_Name AS ResidentFirstName, r.Last_Name AS ResidentLastName
        FROM Visitor_Entries v
        JOIN residents r ON v.Resident_ID = r.Resident_ID
        WHERE v.Entry_ID = ?
    ");
    $stmt->bind_param("i", $Entry_ID);

    // Execute and fetch results for the visit details
    $stmt->execute();
    $visit_result = $stmt->get_result();

    if ($visit_result->num_rows > 0) {
        $visit_row = $visit_result->fetch_assoc();

        // Fetch visitors associated with the Entry_ID
        $visitor_stmt = $conn->prepare("
            SELECT Visitor_CNIC, First_Name, Last_Name 
            FROM Visitors 
            WHERE Entry_ID = ?
        ");
        $visitor_stmt->bind_param("i", $Entry_ID);
        $visitor_stmt->execute();
        $visitor_result = $visitor_stmt->get_result();
        ?>

        <h2>Visit Details</h2>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; background-color: #f2f2f2; color: #34838a;">Visit Information</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Entry ID:</strong></td>
                    <td><?= htmlspecialchars($visit_row['Entry_ID']) ?></td>
                </tr>
                <tr>
                    <td><strong>Visit Date:</strong></td>
                    <td><?= htmlspecialchars($visit_row['Entry_Date']) ?></td>
                </tr>
                <tr>
                    <td><strong>Purpose of Visit:</strong></td>
                    <td><?= htmlspecialchars($visit_row['Purpose_of_Visit']) ?></td>
                </tr>
            </tbody>
        </table>

        <h3>Resident Information</h3>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%; margin-bottom: 20px;">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center; background-color: #f2f2f2; color: #34838a;">Resident Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Resident ID:</strong></td>
                    <td><?= htmlspecialchars($visit_row['Resident_ID']) ?></td>
                </tr>
                <tr>
                    <td><strong>Resident Name:</strong></td>
                    <td><?= htmlspecialchars($visit_row['ResidentFirstName'] . " " . $visit_row['ResidentLastName']) ?></td>
                </tr>
            </tbody>
        </table>

        <h3>Visitors Information</h3>
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="background-color: #f2f2f2; color: #34838a;">Visitor CNIC</th>
                    <th style="background-color: #f2f2f2; color: #34838a;">Visitor First Name</th>
                    <th style="background-color: #f2f2f2; color: #34838a;">Visitor Last Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($visitor_result->num_rows > 0) {
                    while ($visitor_row = $visitor_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($visitor_row['Visitor_CNIC']) . "</td>";
                        echo "<td>" . htmlspecialchars($visitor_row['First_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($visitor_row['Last_Name']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align: center; color: red;'>No visitors found for this entry.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Close visitor statement
        $visitor_stmt->close();
    } else {
        echo "<div style='color:red;'>Invalid visit ID. No records found.</div>";
    }

    // Close the visit statement
    $stmt->close();
} else {
    echo "<div style='color:red;'>No visit ID provided.</div>";
}

// Close the database connection
$conn->close();

?>
