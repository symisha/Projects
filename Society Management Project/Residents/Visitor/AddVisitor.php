<?php
require_once('../../Includes/config.php');
require_once('MakeQR.php');
//session_start();
if(isset($_SESSION['Resident']))
if ($_SESSION['Resident'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Resident.php?noiframe=true");
}
if (/*$logged != true && $_SESSION['account'] == 'resident'*/1) {
    $Resident_ID = 1000068; // Placeholder for demonstration

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve common data
        $email = htmlspecialchars($_POST['Email']);
        $purpose = htmlspecialchars($_POST['Purpose_of_Visit']);
        $vehicle = htmlspecialchars($_POST['Vehicle_Number']);
        $entry = date('Y-m-d H:i:s', strtotime("now"));
        $exit = date('Y-m-d H:i:s', strtotime('+1 day'));
        // Step 1: Insert into `Visitor_Entries`
        $stmt = $conn->prepare("INSERT INTO Visitor_Entries (Resident_ID, Email, Entry_Date, Exit_Date, Vehicle_Number, Purpose_of_Visit) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $Resident_ID, $email, $entry, $exit, $vehicle, $purpose);

        if ($stmt->execute()) {
            // Step 2: Retrieve the newly created `Entry_ID`
            $Entry_ID = $stmt->insert_id;

            // Step 3: Insert each visitor into the `Visitors` table
            $visitor_count = intval($_POST['visitor_count']);
            $visitor_stmt = $conn->prepare("INSERT INTO Visitors (Entry_ID, Visitor_CNIC, First_Name, Last_Name) VALUES (?, ?, ?, ?)");
            
            for ($i = 0; $i < $visitor_count; $i++) {
                $first_name = htmlspecialchars($_POST["First_Name_$i"]);
                $last_name = htmlspecialchars($_POST["Last_Name_$i"]);
                $cnic = htmlspecialchars($_POST["Visitor_CNIC_$i"]);

                $visitor_stmt->bind_param("isss", $Entry_ID, $cnic, $first_name, $last_name);
                $visitor_stmt->execute();
            }

            echo '<div class="alert alert-success">Visitors added successfully!</div>';
            //header("Location: MakeQR.php");

            $qr_data = 'http://localhost/Project1/DB/Project/Residents/Visitor/verify.php?Entry_ID=' . $Entry_ID;
            $qr_file = __DIR__ . '/visit_qr_' . $Entry_ID . '.png';
            $subject = "Your Visit QR Code";
            $message = "Please present this QR code upon arrival for easy check-in.";

            MakeQR($Entry_ID, $email, $qr_data, $qr_file, $subject, $message);
        } else {
            echo '<div class="alert alert-danger">Error while adding entry: ' . $stmt->error . '</div>';
        }

        $stmt->close();
    }
} else {
    header("Location: ../login.php");
}
?>
