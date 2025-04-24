<?php
require_once('../Includes/config.php');

if (isset($_GET['resident_id']) && isset($_GET['token'])) {
    $resident_id = $_GET['resident_id'];
    $token = $_GET['token'];

    // Validate the token (ensure it corresponds to the correct resident and hasn't expired)
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE Token = ? AND Resident_ID = ? AND Token_expiry > NOW()");
    $stmt->bind_param("si", $token, $resident_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if token is valid
    if ($result->num_rows == 0) {
        // Invalid token or expired
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               <strong>Error!</strong> Invalid or expired token!
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">×</span>
               </button>
             </div>';
        exit; // Stop the script if the token is invalid
    }
} else {
    // If the parameters are not set, show an error
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
           <strong>Error!</strong> Missing token or resident ID!
           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
             <span aria-hidden="true">×</span>
           </button>
         </div>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture and sanitize the submitted form data
    $Pass = htmlspecialchars($_POST['InputPassword1']);
    $ConfirmPass = htmlspecialchars($_POST['InputPassword2']);

    // Ensure passwords match
    if ($Pass === $ConfirmPass) {
        // Hash the password before storing
        $hashedPass = password_hash($Pass, PASSWORD_DEFAULT);

        // Prepare the SQL statement to update the password
        $stmt = $conn->prepare("UPDATE residents SET Password = ? WHERE Resident_ID = ?");
        $stmt->bind_param("si", $hashedPass, $resident_id); // 'si' for string (password) and integer (resident_id)

        // Execute the statement and check for success
        if ($stmt->execute()) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Success!</strong> Your password has been updated successfully!
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                   <strong>Error!</strong> We are facing some technical issues, and your password was not updated successfully!
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">×</span>
                   </button>
                 </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
               <strong>Error!</strong> Passwords do not match!
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">×</span>
               </button>
             </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Set Password</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4 form login-form">
                <h2 class="text-center">Set Password</h2>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?token=" . urlencode($token) . "&resident_id=" . urlencode($resident_id)); ?>" method="post">
                    <div class="form-group">
                        <label for="InputPassword1">Password</label>
                        <input type="password" class="form-control" id="InputPassword1" name="InputPassword1" required>
                        <small id="InputPassword1Help" class="form-text text-muted">Don't share your password with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="InputPassword2">Confirm Password</label>
                        <input type="password" class="form-control" id="InputPassword2" name="InputPassword2" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
