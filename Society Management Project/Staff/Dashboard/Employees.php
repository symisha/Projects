<?php 
  require_once("../../Includes/config.php"); 
  session_start();
if(isset($_SESSION['Resident']))
if ($_SESSION['Resident'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Resident.php?noiframe=true");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Employees.css">
    <link rel="shortcut icon" href="Logo3.jpg">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
</head>

<body>
    <div class="sidebar">
        <div class="logo-details">
            <i><img src="1.png" alt="logo" width="100px" height="100px"></i>
            <span class="logo_name">HMS</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="Employees.php" class="active">
                    <i class='bx bx-grid-alt'></i>
                    <span class="links_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="EmployeesRequest.php" target="content-frame">
                    <i class='bx bx-task'></i>
                    <span class="links_name">Requests</span>
                </a>
            </li>
            <li class="log_out">
            <a href="../../Admin/logout_user.php">
                    <i class='bx bx-log-out'></i>
                    <span class="links_name">Log out</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Dashboard</span>
            </div>
            <div class="profile-details">
                <span class="admin_name"> <?php echo $_SESSION['Username']; ?></span>
            </div>
        </nav>

        <div class="home-content">
            <!-- Load other pages here without reloading the dashboard layout -->
            <iframe name="content-frame" style="width:100%; height:100vh; border:none;"></iframe>
        </div>
    </section>
</body>
</html>
