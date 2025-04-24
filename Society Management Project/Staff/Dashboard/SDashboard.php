
<?php
// Start the session
session_start();
require_once("../../Includes/config.php");
//require_once("../../Includes/session.php");
/*session_start();
if(isset($_SESSION['Resident']))
if ($_SESSION['Resident'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Resident.php?noiframe=true");
}*/
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
        <a href="SDash.php" target="iframe-content" >
        <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="EmployeesRequest.php" target="iframe-content">
            <i class='bx bx-task'></i>
            <span class="links_name">Services Requests</span>
          </a>
        </li>
        <li class="logout-btn">
            <a href="../Slogout_user.php">
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
      <iframe name="iframe-content" src="SDash.php" style="width: 100%; height: 600px; border: none;"></iframe>
</div>
   
  </div>

</body>
</html>