<?php 
require_once("../../Includes/config.php"); 
//require_once("../../Includes/session.php"); 
session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}
$EnAdminID = $_SESSION["Employee_ID"];

// Initialize variables
$editData = null;
$success = false;

// Handle Form Submission for Adding and Editing Announcements
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Title = $_POST["Title"]; 
    $Content = $_POST["Content"]; 

    if ($_POST["action"] === "add") {
        // Add announcement
        $Created_At = date('Y-m-d H:i:s');
        $query = "INSERT INTO Announcements (Title, Content, Created_At, Created_By) 
                  VALUES ('$Title', '$Content', '$Created_At', '$EnAdminID')";
        mysqli_query($conn, $query);
        $success = true;
    } elseif ($_POST["action"] === "edit") {
        // Edit announcement
        $Announcement_ID = $_POST["Announcement_ID"];
        $query = "UPDATE Announcements SET Title = '$Title', Content = '$Content' WHERE Announcement_ID = $Announcement_ID";
        mysqli_query($conn, $query);
        $success = true;
    }
}

// Handle Deleting an Announcement
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["delete"])) {
    $Announcement_ID = $_GET["delete"];
    $query = "DELETE FROM Announcements WHERE Announcement_ID = $Announcement_ID";
    mysqli_query($conn, $query);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle Edit Request
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["edit"])) {
    $Announcement_ID = $_GET["edit"];
    $edit_query = "SELECT * FROM Announcements WHERE Announcement_ID = $Announcement_ID LIMIT 1";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $editData = mysqli_fetch_assoc($edit_result);
    }
}

// Fetch Announcements for Display
$announcements_query = "SELECT * FROM Announcements ORDER BY Created_At DESC";
$announcements_result = mysqli_query($conn, $announcements_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Announcements</title>
    <link rel="stylesheet" href="AddAnnouncement.css">
</head>
<body>
    <div class="announcement-container">
        <h1>Manage Announcements</h1>
        <!-- Form to Add/Edit Announcement -->
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="hidden" name="action" value="<?php echo isset($editData) ? 'edit' : 'add'; ?>">
            <input type="hidden" name="Announcement_ID" value="<?php echo $editData['Announcement_ID'] ?? ''; ?>">

            <div class="form-group">
                <label for="Title">Title</label>
                <input type="text" name="Title" id="Title" class="form-control" 
                       value="<?php echo htmlspecialchars($editData['Title'] ?? ''); ?>" placeholder="Enter title" required>
            </div>
            <div class="form-group">
                <label for="Content">Description:</label>
                <textarea name="Content" id="Content" rows="5" class="form-control" placeholder="Enter announcement description" required><?php echo htmlspecialchars($editData['Content'] ?? ''); ?></textarea>
            </div>
            <button type="submit" style="margin: 10px 0"><?php echo isset($editData) ? 'Update Announcement' : 'Add Announcement'; ?></button>
            <?php if ($success) { ?>
                <p style="color: #82c055;">Announcement submitted successfully!</p>
            <?php } ?>
        </form>

        <hr>

        <!-- Display Existing Announcements -->
        <h2>Existing Announcements</h2>
        <?php if ($announcements_result && mysqli_num_rows($announcements_result) > 0): ?>
            <?php while ($announcement = mysqli_fetch_assoc($announcements_result)): ?>
                <div class="announcement">
                    <h3><?php echo htmlspecialchars($announcement['Title']); ?></h3>
                    <p><?php echo htmlspecialchars($announcement['Content']); ?></p>
                    <small><em>Created on: <?php echo htmlspecialchars(date("F j, Y, g:i a", strtotime($announcement['Created_At']))); ?></em></small>
                    <!-- Edit Button -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" style="display: inline;">
                        <input type="hidden" name="edit" value="<?php echo $announcement['Announcement_ID']; ?>">
                        <button type="submit" style="margin: 0 0 0 330px;">Edit</button>
                    </form>
                    <!-- Delete Button -->
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" 
                          onsubmit="return confirm('Are you sure you want to delete this announcement?');" style="display: inline;">
                        <input type="hidden" name="delete" value="<?php echo $announcement['Announcement_ID']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
