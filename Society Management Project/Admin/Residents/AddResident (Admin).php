<?php

require_once("../../Includes/config.php");
//require_once("../Includes/session.php");
require_once("../SendEmail.php");
session_start();
if(isset($_SESSION['Admin']))
if ($_SESSION['Admin'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Admin.php?noiframe=true");
}

//head means either the owner or the renter 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $AdminID = 1000001;
    $House_ID = htmlspecialchars($_POST['House_No']);

    if (isset($_POST['action']) && $_POST['action'] === 'checkHouse') {    
        $stmt = $conn->prepare("SELECT 1 FROM Houses WHERE house_no = ?");
        $stmt->bind_param("i", $House_No);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
    
        header('Content-Type: application/json');
        echo json_encode(['exists' => $count > 0]);
        exit;
    }
    
    // Head Resident (optional)
    $Head_First_Name = htmlspecialchars($_POST['Head_First_Name'] ?? '');
    $Head_Last_Name = htmlspecialchars($_POST['Head_Last_Name'] ?? '');
    $Head_CNIC = htmlspecialchars($_POST['Head_CNIC'] ?? '');
    $Head_Email = htmlspecialchars($_POST['Head_Email'] ?? '');
    $Head_Status = htmlspecialchars($_POST['Head_Status'] ?? '');

    // Additional Residents
    $First_Names = $_POST['First_Name'] ?? [];
    $Last_Names = $_POST['Last_Name'] ?? [];
    $CNICs = $_POST['CNIC'] ?? [];
    $Emails = $_POST['Email'] ?? [];
   // $Headed_By = htmlspecialchars($_POST['headed_by'] ?? null); // Manual headed_by input for additional residents

    $conn->begin_transaction();
    try {
        $Head_Resident_ID = null;

        // Insert head resident if provided
        if ($Head_First_Name && $Head_CNIC && $Head_Email && $Head_Status) {
            $stmt = $conn->prepare("INSERT INTO residents (First_Name, Last_Name, CNIC, Email, House_ID, Entry_Admin_ID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $Head_First_Name, $Head_Last_Name, $Head_CNIC, $Head_Email, $House_ID, $AdminID);
            $stmt->execute();
            $Head_Resident_ID = $stmt->insert_id;

            // Generate token for password setup
            $token = bin2hex(random_bytes(50));   
            $expiry_date = date('Y-m-d H:i:s', strtotime('+1 day'));
            $SaveToken = $conn->prepare("INSERT INTO password_resets (Resident_ID, Token, Token_expiry) VALUES (?, ?, ?)");
            $SaveToken->bind_param("iss", $Head_Resident_ID, $token, $expiry_date);
            $SaveToken->execute();
            $SaveToken->close();
            $link = "http://localhost/Project1/DB/Project/Admin/SetPassword.php?token={$token}&resident_id={$Head_Resident_ID}";
            $Subject = 'Set Up Your Password';
            $Body = "<h1>Hello, {$Head_First_Name}</h1>
                     <p>Your Resident ID is <b>{$Head_Resident_ID}</b>. Click the link below to set your password:</p>
                     <p><a href='{$link}'>Set your password</a></p>
                     <p>This link will expire in 24 hours.</p>";

            sendEmail($Head_Email, $Subject, $Body, null, null, null);
            $stmt->close();

            // Handle ownership or rental
            if ($Head_Status === 'owner') {
                $stmt = $conn->prepare("INSERT INTO Resident_House_Ownership (Resident_ID, House_ID) VALUES (?, ?)");
            } elseif ($Head_Status === 'renter') {
                $stmt = $conn->prepare("INSERT INTO Resident_House_Rent (Resident_ID, House_ID) VALUES (?, ?)");
            }
            if ($stmt) {
                $stmt->bind_param("ii", $Head_Resident_ID, $House_ID);
                $stmt->execute();
                $stmt->close();
            }
        }
        foreach ($First_Names as $index => $First_Name) {
            $Last_Name = htmlspecialchars($Last_Names[$index]);
            $CNIC = htmlspecialchars($CNICs[$index]);
            $Email = htmlspecialchars($Emails[$index]);
        /*
            // Validate headed_by if provided
            $Final_Headed_By = $Head_Resident_ID ?? $Headed_By;
            if (empty($Final_Headed_By)) {
                throw new Exception("No 'headed_by' value provided for additional residents.");
            }
        
            // Check if headed_by is valid (owner or renter)
            if ($Final_Headed_By) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM residents r
                                        JOIN Resident_House_Ownership o ON r.Resident_ID = o.Resident_ID
                                        WHERE r.Resident_ID = ? AND o.House_ID = ?");
                $stmt->bind_param("ii", $Final_Headed_By, $House_ID);
                $stmt->execute();
                $stmt->bind_result($count_owner);
                $stmt->fetch();
                $stmt->close();
        
                if ($count_owner == 0) {
                    // Check for renter status
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM residents r
                                            JOIN Resident_House_Rent rhr ON r.Resident_ID = rhr.Resident_ID
                                            WHERE r.Resident_ID = ? AND rhr.House_ID = ?");
                    $stmt->bind_param("ii", $Final_Headed_By, $House_ID);
                    $stmt->execute();
                    $stmt->bind_result($count_renter);
                    $stmt->fetch();
                    $stmt->close();
        
                    if ($count_renter == 0) {
                        throw new Exception("The 'headed_by' resident is neither an owner nor a renter.");
                    }
                }
            }
        */
            // Insert additional resident
            $stmt = $conn->prepare("INSERT INTO residents (First_Name, Last_Name, CNIC, Email, House_ID, Entry_Admin_ID) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssii", $First_Name, $Last_Name, $CNIC, $Email, $House_ID, $AdminID);
            $stmt->execute();
            $Resident_ID = $stmt->insert_id;
            
            // Generate token for password setup
            $token = bin2hex(random_bytes(50));
            $expiry_date = date('Y-m-d H:i:s', strtotime('+1 day'));
            $SaveToken = $conn->prepare("INSERT INTO password_resets (Resident_ID, Token, Token_expiry) VALUES (?, ?, ?)");
            $SaveToken->bind_param("iss", $Resident_ID, $token, $expiry_date);
            $SaveToken->execute();
            $SaveToken->close();
            $stmt->close();
            $link = "http://localhost/Project1/DB/Project/Admin/SetPassword.php?token={$token}&resident_id={$Resident_ID}";
            $Subject = 'Set Up Your Password';
            $Body = "<h1>Hello, {$First_Name}</h1>
                     <p>Your Resident ID is <b>{$Resident_ID}</b>. Click the link below to set your password:</p>
                     <p><a href='{$link}'>Set your password</a></p>
                     <p>This link will expire in 24 hours.</p>";
        
            sendEmail($Email, $Subject, $Body, null, null, null);

        }     

        $conn->commit();
        echo '<div class="alert alert-success">Residents added successfully, and emails have been sent!</div>';
    } catch (Exception $e) {
        $conn->rollback();
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Residents</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Dynamically add additional residents
        function addResident() {
            const container = document.getElementById('additionalResidents');
            const newResident = document.createElement('div');
            newResident.classList.add('resident-group', 'mt-3');
            newResident.innerHTML = `
                <div class="form-group col-md-6">
                    <input type="text" name="First_Name[]" placeholder="First Name" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="text" name="Last_Name[]" placeholder="Last Name" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="tel" name="CNIC[]" pattern="[0-9]{13}" placeholder="CNIC" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <input type="email" name="Email[]" placeholder="E-mail" class="form-control" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeResident(this)">Remove Resident</button>
                <hr>
            `;
            container.appendChild(newResident);
        }

        // Remove a resident entry
        function removeResident(button) {
            button.parentElement.remove();
        }

        // Run the check on page load and whenever the user interacts with head resident fields
        window.onload = checkHeadResidentFields;
        document.querySelector('input[name="Head_First_Name"]').addEventListener('input', checkHeadResidentFields);
        document.querySelector('input[name="Head_Last_Name"]').addEventListener('input', checkHeadResidentFields);
        document.querySelector('input[name="Head_CNIC"]').addEventListener('input', checkHeadResidentFields);
        document.querySelector('input[name="Head_Email"]').addEventListener('input', checkHeadResidentFields);
    </script>
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Add Residents</h2>
        <form action="AddResident (Admin).php" method="post">
            <!-- House Details -->
            <div class="form-group">
    <label for="House_No">House Number:</label>
    <input 
        type="number" 
        name="House_No" 
        id="House_No" 
        placeholder="Enter House Number" 
        class="form-control" 
        required 
        min="1234" 
        max="1400"
        onblur="checkHouseNumber()">
    <small id="houseError" class="text-danger"></small>
</div>

<script>
    function checkHouseNumber() {
        const houseInput = document.getElementById('House_No');
        const houseError = document.getElementById('houseError');

        if (houseInput.value.trim() === "") return; // Skip if the field is empty

        // AJAX Request
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "AddResident (Admin).php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.exists) {
                    houseError.textContent = ""; // Clear error if valid
                } else {
                    houseError.textContent = "House number does not exist.";
                }
            }
        };
        xhr.send("action=checkHouse&House_No=" + encodeURIComponent(houseInput.value));
    }
</script>


            <!-- Head Resident -->
            <fieldset>
                <legend>Head Resident (Optional)</legend>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <input type="text" name="Head_First_Name" placeholder="First Name" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="text" name="Head_Last_Name" placeholder="Last Name" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="tel" name="Head_CNIC" pattern="[0-9]{13}" placeholder="CNIC" class="form-control">
                    </div>
                    <div class="form-group col-md-3">
                        <input type="email" name="Head_Email" placeholder="E-mail" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="Head_Status">Head Status:</label>
                    <select name="Head_Status" id="Head_Status" class="form-control">
                        <option value="">None</option>
                        <option value="owner">Owner</option>
                        <option value="renter">Renter</option>
                    </select>
                </div>
            </fieldset>

            <!-- Additional Residents -->
            <fieldset>
                <legend>Additional Residents</legend>
                <div id="additionalResidents"></div>
                <button type="button" class="btn btn-primary btn-sm" onclick="addResident()">Add Another Resident</button>
            </fieldset>

            <!-- Submit -->
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success btn-block">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
