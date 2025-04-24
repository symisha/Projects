<?php  
require_once("../../Includes/config.php"); 
require_once("../../Includes/session.php"); 

// Redirect if not logged in
if (!isset($_SESSION['logged']) || $_SESSION['logged'] === false) {
    header("Location:../../login - Resident.php");
    exit;
}// Fetch Admin Details
$Employee_ID = $_SESSION['Employee_ID'];
$resident_query = "SELECT First_Name, Last_Name, CNIC, Email FROM Employees WHERE Employee_ID = '$Employee_ID'";
$result = mysqli_query($conn, $resident_query);

// Initialize variables for display
$first_name = $last_name = $email = $cnic = "";

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $first_name = $row['First_Name'];
    $last_name = $row['Last_Name'];
    $email = $row['Email'];
    $cnic = $row['CNIC'];
   // $Department_ID= $row['Department_ID'];
} else {
    // Fallback in case of no data (optional)
    $first_name = "Employee";
}
// Count total residents
$resident_count_query = "SELECT COUNT(*) AS total_residents FROM residents";
$resident_count_result = mysqli_query($conn, $resident_count_query);
$resident_count = 0;
if ($resident_count_result) {
    $resident_row = mysqli_fetch_assoc($resident_count_result);
    $resident_count = $resident_row['total_residents'];
}

// Count total staff
$staff_count_query = "SELECT COUNT(*) AS total_staff FROM Employees";
$staff_count_result = mysqli_query($conn, $staff_count_query);
$staff_count = 0;
if ($staff_count_result) {
    $staff_row = mysqli_fetch_assoc($staff_count_result);
    $staff_count = $staff_row['total_staff'];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="ADashboard.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<body>
    <!-- Main Content -->
    <div class="main-content">
        <div id="content">
        <h1 style="color: #05555c; padding: 0 10px; font-size: 32px; border-bottom: 2px solid #05555c;">Personal Information</h1>
        <p style="padding: 0 10px;"><strong>ID:</strong> <?php echo htmlspecialchars(string: $Employee_ID);?></p>
        <p style="padding: 0 10px;"><strong>Name:</strong> <?php echo htmlspecialchars($first_name . " " . $last_name); ?></p>
        <p style="padding: 0 10px;"><strong>CNIC:</strong> <?php echo htmlspecialchars($cnic);?></p>
        <p style="padding: 0 10px;"><strong>Email:</strong> <?php echo htmlspecialchars($email);?></p>         
      
      <!-- Total Stats Section -->
      <h1 style="color: #05555c; padding: 10px 0 10px 10px; font-size: 32px; border-bottom: 2px solid #05555c;">Population Summary</h1>
        <div class="stats-section">
          <div class="stat-box">
            <i class='bx bxs-user'></i>
            <h3><?php echo $resident_count; ?></h3>
            <p>Total Residents</p>
          </div>
          <div class="stat-box">
            <i class='bx bxs-group'></i>
            <h3><?php echo $staff_count; ?></h3>
            <p>Total Staff</p>
          </div>
        </div>
        </div>
    </div>
  </div>
</body>
</html>