<?php 
require_once("../../Includes/config.php"); 
session_start();
if(isset($_SESSION['Resident']))
if ($_SESSION['Resident'] != true || $_SESSION["logged"] != true) {
  header("Location: ../login - Resident.php?noiframe=true");
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requests = $_POST["requests"];
    $residentID = $_SESSION["Resident_ID"];

    foreach ($requests as $request) {
        $requestType = $request["requestType"];
        $description = $request["description"];
        $price = $request["price"];

        // Validate input
        if (empty($requestType) || empty($description) || empty($price)) {
            $errorMessage = "Please fill in all fields for each request.";
            break;
        }

        // Insert maintenance request into database
        $query = "INSERT INTO Maintenance_Requests (Resident_ID, Request_Type, Description, Price) 
                  VALUES ('$residentID', '$requestType', '$description', '$price')";

        if (!mysqli_query($conn, $query)) {
            $errorMessage = "Error submitting request: " . mysqli_error($conn);
            break;
        }
    }

    if (empty($errorMessage)) {
        $successMessage = "All maintenance requests submitted successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Maintenance_Req.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h1>Maintenance Request</h1>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
            <div id="requests-container">
                <div class="request-entry">
                    <label for="requestType">Request Type:</label>
                    <select name="requests[0][requestType]" required>
                        <option value="">Select</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Carpentry">Carpentry</option>
                        <option value="Other">Other</option>
                    </select><br><br>

                    <label for="description">Description:</label>
                    <textarea name="requests[0][description]" required></textarea><br><br>

                    <label for="price">Estimated Price:</label>
                    <input type="number" name="requests[0][price]" step="0.01" min="0" required><br><br>
                </div>
            </div>
            <button type="button" class="add-request-btn" onclick="addRequest()">Add Another Request</button>
            <button type="submit">Submit Requests</button>
            <p id="error-message" style="color:red;"><?php echo $errorMessage; ?></p>
            <p id="success-message" style="color:green;"><?php echo $successMessage; ?></p>
        </form>
    </div>

    <script>
        let requestIndex = 1;
        function addRequest() {
            const container = document.getElementById('requests-container');
            const newRequest = `
                <div class="request-entry">
                    <label for="requestType">Request Type:</label>
                    <select name="requests[${requestIndex}][requestType]" required>
                        <option value="">Select</option>
                        <option value="Plumbing">Plumbing</option>
                        <option value="Electrical">Electrical</option>
                        <option value="Carpentry">Carpentry</option>
                        <option value="Other">Other</option>
                    </select><br><br>

                    <label for="description">Description:</label>
                    <textarea name="requests[${requestIndex}][description]" required></textarea><br><br>

                    <label for="price">Estimated Price:</label>
                    <input type="number" name="requests[${requestIndex}][price]" step="0.01" min="0" required><br><br>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newRequest);
            requestIndex++;
        }
    </script>
</body>
</html>
