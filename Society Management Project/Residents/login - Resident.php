<?php
session_start();
require_once("../Includes/config.php");

$error_message = ''; // Variable to store error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Resident_ID = $_POST['Resident_ID'];
    $password = $_POST['password'];

    // Clean up input
    $Resident_ID = stripslashes($Resident_ID);
    $password = stripslashes($password);
    $Resident_ID = mysqli_real_escape_string($conn, $Resident_ID);
    $password = mysqli_real_escape_string($conn, $password);

    // Search only the resident table by Resident_ID
    $sql = "SELECT * FROM residents WHERE Resident_ID = '$Resident_ID'";
    $result = mysqli_query($conn, $sql);

    // Check if query executed successfully
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // Verify the password
        if (password_verify($password, $row['Password'])) {
            // Password verified, set session variables
            $_SESSION['logged'] = true;
            $_SESSION['Username'] = $row['First_Name'];
            $_SESSION['Resident_ID'] = $Resident_ID;
            $_SESSION['account'] = 'resident';
            $_SESSION['Resident'] = true;

            // Redirect to resident dashboard
            header("Location: ../Residents/Dashboard/RDashboard.php");
            exit; // Important to prevent further execution
        } else {
            $error_message = "Invalid password. Please try again."; // Set error message for wrong password
        }
    } else {
        $error_message = "Invalid Resident ID. Please try again."; // Set error message for wrong Resident ID
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="Logo3.jpg"/>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
    
                <h2 class="text-center">Login Form</h2>
                <p class="text-center">Welcome to Housing Society</p>

                <!-- Display error message if there is one -->
                <?php if (!empty($error_message)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php } ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <input type="text" name="Resident_ID" placeholder="Resident ID" class="form-control" required>
                    </div>    
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group">
                       <input class="form-control button" type="submit" name="login" value="Login">
                    </div>
                    <hr>
                    <div class="link login-link text-center"><a href="../home/home.php">Back To Home</a></div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
