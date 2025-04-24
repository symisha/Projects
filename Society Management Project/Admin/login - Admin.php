<?php
require_once("../Includes/config.php");

$error_message = ''; // Variable to store error message

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Employee_ID = $_POST['Employee_ID'];
    $password = $_POST['password'];

    // Clean up input
    $Employee_ID = stripslashes($Employee_ID);
    $password = stripslashes($password);
    $Employee_ID = mysqli_real_escape_string($conn, $Employee_ID);
    $password = mysqli_real_escape_string($conn, $password);

    // Search only the admin table
    $sql = "SELECT Department_ID , First_Name FROM Employees WHERE Employee_ID = '$Employee_ID' AND Password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['logged'] = true;
        $_SESSION['Employee_ID'] = $Employee_ID;
        $_SESSION['Department'] = $row['Department_ID'];
        $_SESSION['Username'] = $row['First_Name'];
        $_SESSION['Admin'] = true;

        $dep = $row['Department_ID'];
        if($dep == 1001)
        header("Location: ../Admin/Dashboard/ADashboard.php");
        else if($dep == 1002)
        header("Location: ../Admin/Dashboard/ADashboard.php");
        else if($dep == '1003')
        header("Location: ../Admin/Dashboard/SecurityDash.php");
        exit;
    } else {
        $error_message = "Invalid Employee ID or password."; // Set error message for incorrect login
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
                        <input type="text" name="Employee_ID" placeholder="Employee_ID" class="form-control" required>
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
