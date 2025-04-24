<?php
// Start the session
require_once("../../Includes/config.php");
//require_once("../../Includes/session.php");
session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="ADashboard.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo-details">
        <i>
          <img src="1.png" alt="logo" width="100px" height="100px">
        </i>
      </div>
      <ul class="nav-links">
        <li>
          <a href="ADash.php" target="iframe-content">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        <li>
          <!-- <a href="#" onclick="loadPage('../Announcement/AddAnnouncement.php')">
            <i class='bx bx-bell'></i>
            <span class="links_name">Announcement</span>
          </a> -->
          <a href="../Announcement/AddAnnouncement.php" target="iframe-content">
            <i class='bx bx-bell'></i>
            <span class="links_name">Announcement</span>
          </a>
        </li>
        <li>
          <a href="../DisplayMainReq/DisplayMainReq.php" target="iframe-content">
            <i class='bx bx-wrench'></i>
            <span class="links_name">Maintainence Requests</span>
          </a>
        </li>
        <!--<li>
          <a href="../Home_Services/home_ser.php" target="iframe-content">
            <i class='bx bx-user-circle'></i>
            <span class="links_name">Home Services</span>
          </a>
        </li>-->
        <li>
          <a href="../Houses.php" target="iframe-content">
            <i class='bx bx-map'></i>
            <span class="links_name">Houses</span>
          </a>
        </li>
        <li>
          <a href="../Admin_Feedback/Admin_Feedback.php" target="iframe-content">
            <i class='bx bx-message-rounded'></i>
            <span class="links_name">All Feedbacks</span>
          </a>
        </li>
        <li>
          <a href="../Residents/R_ManagementUI.php" target="iframe-content">
            <i class='bx bx-user'></i>
            <span class="links_name">Manage Resident</span>
          </a>
        </li>
        <li>
          <a href="../Residents/ViewResident.php" target="iframe-content">
            <i class='bx bx-group'></i>
            <span class="links_name">All Residents</span>
          </a>
        </li>
        <li>
          <a href="../Visitors/ViewVisitors.php" target="iframe-content">
            <i class='bx bx-group'></i>
            <span class="links_name">All Visitors</span>
          </a>
        </li>
        <li>
          <a href="../../change_password.php" target="iframe-content">
            <i class='bx bx-key'></i>
            <span class="links_name">Change Password</span>
          </a>
        </li>
        
        <li class="logout-btn">
            <a href="../Alogout_user.php">
                   <i class='bx bx-log-out'></i>
                    <span class="links_name">Log Out</span>
            </a>
        </li>
      </ul>
    </div>
    
     <!-- Main Content Area -->
     <div class="main-content-area">
      <!-- Top Bar -->
      <div class="top-bar">
        <div class="greeting">
            <i class='fas fa-user-circle'></i>
                <span>Hello! <?php echo $_SESSION['Username']; ?></span>
        </div>
      </div>
        <iframe name="iframe-content" src="ADash.php" style="width: 100%; height: 600px; border: none;"></iframe>
  </div>
</div>
</body>
</html>