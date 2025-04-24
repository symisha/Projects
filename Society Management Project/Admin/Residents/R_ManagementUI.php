<?php
require_once("../../Includes/config.php");
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
    <title>Resident Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9fafb;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            margin-top: 0;
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

        .grid {
            margin-top: -20px; /* Moves the grid and its cards upward */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: #f4f4f9;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .card i {
            font-size: 36px;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #333;
        }

        .card.insert i {
            color: #4CAF50;
        }

        .card.update i {
            color: #2196F3;
        }

        .card.delete i {
            color: #f44336;
        }

        footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Resident Management</h1>
            <p>Manage your residents with ease</p>
        </div>
        <div class="grid">
            <div class="card insert">
                <i class="fas fa-user-plus"></i>
                <a href="../Residents/AddResident (Admin).php" target="iframe-content"><h3>Insert Resident</h3></a>
            </div>
            <div class="card update">
                <i class="fas fa-user-edit"></i>
                <a href="../Residents/UpdateResident.php" target = "iframe-content"><h3>Update Resident</h3></a>
            </div>
            <div class="card delete">
                <i class="fas fa-user-minus"></i>
                <a href="../Residents/DeleteResident.php" target = "iframe-content"><h3>Delete Resident</h3></a>
            </div>
        </div>
        <footer>
            &copy; 2024 Your Society Management System
        </footer>
    </div>
</body>
</html>
 