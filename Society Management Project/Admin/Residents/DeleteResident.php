<?php
require_once("../../Includes/config.php");
session_start();
if (isset($_SESSION['Admin'])) {
    if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
        header("Location: ../login - Admin.php?noiframe=true");
    }
}

// Handle the delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $delete_query = "DELETE FROM residents WHERE Resident_ID = '$delete_id'";
    if ($conn->query($delete_query)) {
        $success_message = "Resident deleted successfully.";
    } else {
        $error_message = "Failed to delete the resident: " . $conn->error;
    }
}

// Handle the search operation
$search_term = '';
if (isset($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    $query = "SELECT * FROM residents WHERE Resident_ID LIKE '%$search_term%'";
} else {
    $query = "SELECT * FROM residents";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Resident</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
}

/* Container Styles */
.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Heading */
h2 {
    margin-bottom: 20px;
    font-size: 24px;
    text-align: center;
    color: #333;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

thead tr {
    background-color: #38b6a5;
    color: #fff;
    text-align: left;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
}

tbody tr:hover {
    background-color: #f1f1f1;
}

/* Buttons */
.delete-button {
    display: inline-block;
    padding: 6px 12px;
    color: #fff;
    background-color: #d9534f;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
}

.delete-button:hover {
    background-color: #c9302c;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Resident</h1>

        <!-- Display success or error messages -->
        <?php if (isset($success_message)) { ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php } ?>
        <?php if (isset($error_message)) { ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php } ?>

        <!-- Search bar -->
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by Resident ID" value="<?php echo htmlspecialchars($search_term); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Residents table -->
        <table>
            <thead>
                <tr>
                    <th>Resident ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>House ID</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['Resident_ID']; ?></td>
                            <td><?php echo $row['First_Name']; ?></td>
                            <td><?php echo $row['Last_Name']; ?></td>
                            <td><?php echo $row['House_ID']; ?></td>
                            <td>
                                <a href="?delete_id=<?php echo $row['Resident_ID']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this resident?');">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No residents found</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
