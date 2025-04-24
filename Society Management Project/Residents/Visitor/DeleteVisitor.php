<?php

require_once("../../Includes/config.php");

session_start();
// Ensure the resident is logged in
if (!isset($_SESSION['Resident']) || $_SESSION['Resident'] !== true || !isset($_SESSION['Resident_ID'])) {
    header("Location: ../login - Resident.php?noiframe=true");
    exit;
}

$logged_in_resident_id = $_SESSION['Resident_ID']; // Get the logged-in resident's ID

// Handle the delete operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['entry_id'])) {
    $entry_id = $_POST['entry_id'];

    // Delete the visitor entry for the logged-in resident
    $sql = "DELETE FROM Visitor_Entries WHERE Entry_ID = ? AND Resident_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $entry_id, $logged_in_resident_id);

    if ($stmt->execute()) {
        $message = "Visitor entry deleted successfully!";
    } else {
        $message = "Error deleting visitor entry: " . $conn->error;
    }

    $stmt->close();
}

// Fetch all visitor entries for the logged-in resident
$sql = "SELECT * FROM Visitor_Entries WHERE Resident_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $logged_in_resident_id);
$stmt->execute();
$result = $stmt->get_result();
$visitor_entries = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Visitor Entries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 900px;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        table th, table td {
            text-align: center;
            padding: 10px;
        }

        .btn-delete {
            background-color: #FF4136;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #FF5733;
        }

        .header {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Delete Visitor Entries</h1>
            <p>Below are your visitor entries. You can delete any of them.</p>
        </div>

        <!-- Display success or error message -->
        <?php if (isset($message)) : ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Display visitor entries -->
        <?php if (!empty($visitor_entries)) : ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Entry ID</th>
                        <th>Email</th>
                        <th>Entry Date</th>
                        <th>Exit Date</th>
                        <th>Vehicle Number</th>
                        <th>Purpose of Visit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($visitor_entries as $entry) : ?>
                        <tr>
                            <td><?php echo $entry['Entry_ID']; ?></td>
                            <td><?php echo $entry['Email']; ?></td>
                            <td><?php echo $entry['Entry_Date']; ?></td>
                            <td><?php echo $entry['Exit_Date']; ?></td>
                            <td><?php echo $entry['Vehicle_Number']; ?></td>
                            <td><?php echo $entry['Purpose_of_Visit']; ?></td>
                            <td>
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this visitor entry?');">
                                    <input type="hidden" name="entry_id" value="<?php echo $entry['Entry_ID']; ?>">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No visitor entries found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
