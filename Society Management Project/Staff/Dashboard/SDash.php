<?php  
  require_once("../../Includes/config.php"); 
  session_start();
  if(isset($_SESSION['Employee_ID']))
  // Redirect if not logged in
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] === false) {
       header("Location:../../login - Staff.php?noiframe=true");
       exit;
  }

  // Fetch Resident Details
  $Staff_ID = $_SESSION['Employee_ID'];
  $staff_query = "SELECT First_Name, Last_Name, CNIC, Email FROM Staff WHERE Staff_ID = '$Staff_ID'";
  $result = mysqli_query($conn, $staff_query);

  // Initialize variables for display
  $first_name = $last_name = $Email = $cnic = "";

  if ($result && mysqli_num_rows($result) > 0) {
      $row = mysqli_fetch_assoc($result);
      $first_name = $row['First_Name'];
      $last_name = $row['Last_Name'];
      $Email = $row['Email'];
      $cnic = $row['CNIC'];
  } else {
      // Fallback in case of no data (optional)
      $first_name = "Staff";
  }
  // Fetch Announcements
  $announcements_query = "SELECT Title, Content, Created_At FROM Announcements ORDER BY Created_At DESC";
  $announcements_result = mysqli_query($conn, $announcements_query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="SDashboard.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<body>
 <!-- Main Content -->
 <div class="main-content">
      <div id="content">
      <h1 style="color: #05555c; padding: 0 10px; font-size: 32px; border-bottom: 2px solid #05555c;">Personal Information</h1>
      <p style="padding: 0 10px;"><strong>Name:</strong> <?php echo htmlspecialchars($first_name . " " . $last_name); ?></p>
      <p style="padding: 0 10px;"><strong>CNIC:</strong> <?php echo htmlspecialchars($cnic);?></p>
      <p style="padding: 0 10px;"><strong>Email:</strong> <?php echo htmlspecialchars($Email);?></p>
        <!-- <p>Click on the menu to navigate to different pages.</p> -->
         <!-- Announcements Section -->
         <h2 style="color: #05555c; padding: 10px 10px; font-size: 32px; border-bottom: 2px solid #05555c;">Announcements</h2>
      <div id="announcements">
        <?php if ($announcements_result && mysqli_num_rows($announcements_result) > 0): ?>
          <?php while ($announcement = mysqli_fetch_assoc($announcements_result)): ?>
            <div class="announcement">
              <h3><?php echo htmlspecialchars($announcement['Title']); ?></h3>
              <p><?php echo htmlspecialchars($announcement['Content']); ?></p>
              <small><em><?php echo htmlspecialchars(date("F j, Y", strtotime($announcement['Created_At']))); ?></em></small>
              <hr>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>No announcements available at the moment.</p>
        <?php endif; ?>
      </div>
      </div>
 
    </div>
    </body>
    </html>