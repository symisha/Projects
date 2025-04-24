<?php
// Start the session
require_once("../../Includes/config.php");
//require_once("../../Includes/session.php");
session_start();
if(isset($_SESSION['Department']))
if ($_SESSION['Department'] != 1003 || $_SESSION["logged"] != true) {
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
  <style>
    /* Sidebar */
.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh;
    width: 250px;
    background-color: #34838a;
    color: white;
    padding: 0;
    position: relative;
    top: 0;
    left: 0;
    margin: 0;
}

/* Top Bar */
.top-bar {
    position: fixed;
    top: 0;
    left: 250px;
    width: calc(100% - 250px);
    height: 70px;
    background-color: #34838a;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 0 20px;
    z-index: 1000;
    margin: 0;
}

/* Main Content */
.main-content {
    margin-left: 250px; /* Offset for the sidebar */
    margin-top: 70px;  /* Offset for the top bar */
    padding: 20px;
    background-color: #f4f4f4;
    min-height: calc(100vh - 70px); /* Ensure it fills the remaining space */
}

    </style>
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
        
        <li>
          <a href="../../Residents/Visitor/Scanner.php" target="iframe-content">
            <i class='bx bx-user'></i>
            <span class="links_name">Enter Visitors</span>
          </a>
        </li>
        <li>
          <a href="../Visitors/ViewVisitors.php" target="iframe-content">
            <i class='bx bx-user'></i>
            <span class="links_name">View Entries</span>
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
        <iframe name="iframe-content" src="ADash.php" style="width: 100%; height: 500px; border: none;"></iframe>
  </div>
</div>
</body>
</html>