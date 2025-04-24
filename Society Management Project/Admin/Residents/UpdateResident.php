<?php

require_once("../../Includes/config.php"); 
//require_once("../../Includes/session.php"); 

session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}
// Handle the update operation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'];
    $house_number = $_POST['house_number'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $cnic = $_POST['cnic'];
    $email = $_POST['email'];

    // Update the resident data in the database
    $sql = "UPDATE residents SET House_ID = ?, First_Name = ?, Last_name = ?, CNIC = ?, Email = ? WHERE Resident_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssi", $house_number, $first_name, $last_name, $cnic, $email, $resident_id);

    if ($stmt->execute()) {
        $message = "Resident updated successfully!";
    } else {
        $message = "Error updating resident: " . $conn->error;
    }

    $stmt->close();
}

// Fetch the resident data when the page loads or Resident ID is provided
$resident_data = [];
if (isset($_GET['resident_id'])) {
    $resident_id = $_GET['resident_id'];
    $sql = "SELECT * FROM residents WHERE Resident_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $resident_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resident_data = $result->fetch_assoc();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Resident</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;
            max-width: 600px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .header p {
            color: #777;
            font-size: 16px;
        }

        label {
            font-weight: bold;
            margin-top: 15px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Update Resident</h1>
            <p>Fetch and update resident details below.</p>
        </div>

        <!-- Display success or error message -->
        <?php if (isset($message)) : ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Resident ID Input -->
        <form id="fetchForm" method="GET">
            <label for="resident-id">Resident ID:</label>
            <input type="text" name="resident_id" id="resident-id" class="form-control" placeholder="Enter Resident ID" required>
            <button type="submit" class="btn-submit mt-3">Fetch Data</button>
        </form>

        <!-- Update Resident Form -->
        <?php if (!empty($resident_data)) : ?>
            <form id="UpdateResident.php" method="POST" action="">
                <input type="hidden" name="resident_id" value="<?php echo $resident_data['Resident_ID']; ?>">

                <h5 class="mt-4">Resident Details</h5>
                <label for="house-number">House Number:</label>
                <input type="text" name="house_number" id="house-number" class="form-control" placeholder="Enter House Number" value="<?php echo $resident_data['House_ID']; ?>" required>

                <label for="first-name">First Name:</label>
                <input type="text" name="first_name" id="first-name" class="form-control" placeholder="Enter First Name" value="<?php echo $resident_data['First_Name']; ?>" required>

                <label for="last-name">Last Name:</label>
                <input type="text" name="last_name" id="last-name" class="form-control" placeholder="Enter Last Name" value="<?php echo $resident_data['Last_Name']; ?>" required>

                <label for="cnic">CNIC:</label>
                <input type="text" name="cnic" id="cnic" class="form-control" placeholder="Enter CNIC" value="<?php echo $resident_data['CNIC']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="<?php echo $resident_data['Email']; ?>" required>

                <button type="submit" class="btn-submit mt-3">Update</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
