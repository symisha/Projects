<?php 
require_once("../../Includes/config.php"); 
session_start();
if(isset($_SESSION['Resident']))
if ($_SESSION['Resident'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Resident.php?noiframe=true");
}

$residentID = $_SESSION["Resident_ID"]; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedbackType = $_POST["feedback-type"]; 
    $feedbackText = $_POST["feedback-text"]; 

    $query = "INSERT INTO Feedback (Resident_ID, Feedback_Type, Feedback_Text) VALUES ('$residentID', '$feedbackType', '$feedbackText')";
    mysqli_query($conn, $query);

    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Feedback.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Feedback</title>
</head>
<body>
    <div class="feedback-container">
        <h1>Feedback/Complaint</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post">
            <div class="form-group">
                <label for="feedback-type">Type:</label>
                <select id="feedback-type" name="feedback-type" required>
                    <option value="">Select Type</option>
                    <option value="General Feedback">General Feedback</option>
                    <option value="Complaint">Complaint</option>
                </select>
            </div>
            <div class="form-group">
                <label for="feedback-text">Your Feedback/Complaint:</label>
                <textarea id="feedback-text" name="feedback-text" required></textarea>
            </div>
            <button type="submit">Submit</button>
            <?php if (isset($success)) { ?>
            <p style="color: #82c055;">Feedback submitted successfully!</p>
            <?php } ?>
        </form>
    </div>
</body>
</html>