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

    <link rel="stylesheet" href="membership_card.css?v=1">
    <link rel="shortcut icon" href="Logo3.jpg">


    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="membership-container">
        <h1>Membership Cards</h1>
        <div class="membership-cards">
<div class="card diamond">
    <div class="card-header">
        <h2>Diamond Membership</h2>
    </div>
    <div class="card-body">
        <ul>
            <li>Access to exclusive events</li>
            <li>Priority customer support</li>
            <li>Discounts on society services</li>
        </ul>
        <a href="membership_registration.php?CardID=113"><button>Join Now (RS.2500/year)</button></a>
    </div>
</div>

<div class="card golden">
    <div class="card-header">
        <h2>Golden Membership</h2>
    </div>
    <div class="card-body">
        <ul>
            <li>Access to premium services</li>
            <li>Priority access to events</li>
            <li>Discounts on society services</li>
        </ul>
        <a href="membership_registration.php?CardID=112"><button>Join Now (Rs.1500/year)</button></a>
    </div>
</div>

<div class="card silver">
    <div class="card-header">
        <h2>Silver Membership</h2>
    </div>
    <div class="card-body">
        <ul>
            <li>Access to basic services</li>
            <li>Discounts on society services</li>
            <li>Discounts on society services</li>
        </ul>
        <a href="membership_registration.php?CardID=111"><button>Join Now (RS.1000/year)</button></a>
    </div>
</div>
        </div>
    </div>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    </script>
 
</body>

</html>