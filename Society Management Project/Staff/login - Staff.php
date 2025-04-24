<?php
require_once("../Includes/config.php");

$error_message = ''; // Variable to store error message

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Staff_ID = $_POST['Staff_ID'];
    $Password = $_POST['Password'];

    // Clean up input
    $Staff_ID = stripslashes($Staff_ID);
    $Password = stripslashes($Password);
    $Staff_ID = mysqli_real_escape_string($conn, $Staff_ID);
    $Password = mysqli_real_escape_string($conn, $Password);

    // Search only the staff table
    $sql = "SELECT Department_ID , First_Name FROM Staff WHERE Staff_ID = '$Staff_ID' AND Password = '$Password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['logged'] = true;
        $_SESSION['Employee_ID'] = $Staff_ID;
        $_SESSION['Departments'] = $row['Department_ID'];
        $_SESSION['Username'] = $row['First_Name'];
        header("Location: ../Staff/Dashboard/SDashboard.php");
        
    } else {
        $error_message = "Invalid Staff ID or password."; // Set error message for incorrect login
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
                        <input type="text" name="Staff_ID" placeholder="Staff ID" class="form-control" required>
                    </div>    
                    <div class="form-group">
                        <input type="password" name="Password" placeholder="Password" class="form-control" required>
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
