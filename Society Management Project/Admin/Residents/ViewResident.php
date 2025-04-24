<?php
require_once('../../Includes/config.php'); // Update with the correct path to your config

// Initialize an empty search query
$searchQuery = "";

// Check if the form is submitted and fetch the Resident_ID
if (isset($_GET['search'])) {
    $searchQuery = $conn->real_escape_string($_GET['resident_id']);
}

// Modify the query to include search functionality
if ($searchQuery) {
    $query = "SELECT * FROM residents WHERE Resident_ID = '$searchQuery'";
} else {
    $query = "SELECT * FROM residents";
}

$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Residents</title>
</head>
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

    /* Form Styles */
    .search-form {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .search-form input[type="text"] {
        padding: 8px;
        font-size: 16px;
        width: 300px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 10px;
    }

    .search-form button {
        padding: 8px 12px;
        font-size: 16px;
        color: #fff;
        background-color: #38b6a5;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .search-form button:hover {
        background-color: #2a9483;
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
<body>
    <div class="container">
        <h2>Residents List</h2>
        
        <!-- Search Form -->
        <form class="search-form" method="get">
            <input type="text" name="resident_id" placeholder="Enter Resident ID" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" name="search">Search</button>
        </form>
        
        <!-- Residents Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>CNIC</th>
                    <th>Email</th>
                    <th>House ID</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['Resident_ID']; ?></td>
                            <td><?php echo $row['First_Name']; ?></td>
                            <td><?php echo $row['Last_Name']; ?></td>
                            <td><?php echo $row['CNIC']; ?></td>
                            <td><?php echo $row['Email']; ?></td>
                            <td><?php echo $row['House_ID']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">No residents found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
