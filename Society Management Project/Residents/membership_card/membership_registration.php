<?php
require_once("../../Includes/config.php"); 
//include("../../Billing/Payment/Response.php");
session_start();
// Check session
if (!isset($_SESSION['Resident']) || !$_SESSION['Resident'] || !$_SESSION["logged"]) {
    header("Location: ../login - Resident.php?noiframe=true");
    exit();
}

$residentID = $_SESSION['Resident_ID'];
$num = 2;
$membershipType = '';
$membershipFee = '';
  // Get membership details
  $cardID = $_GET["CardID"] ?? '';
  $query = "SELECT Card_Name, Fee FROM MembershipCards WHERE Card_ID = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $cardID);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $row = $result->fetch_assoc()) {
      $membershipType = $row['Card_Name'];
      $membershipFee = $row['Fee'];
  } else {
      throw new Exception("Membership card details not found.");
  }

  $query = "SHOW TABLE STATUS LIKE 'Membership_Bills'";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$billing_id = $row['Auto_increment'];

echo "Next auto-increment value: " . $billing_id;


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="membership_registration.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Registration</title>
</head>
<body>
    <div class="membership-registration-container">
        <h1>Membership Registration</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="CardID" value="<?php echo htmlspecialchars($cardID); ?>">
            <input type="hidden" name="membershipType" value="<?php echo htmlspecialchars($membershipType); ?>">
            <input type="hidden" name="membershipFee" value="<?php echo htmlspecialchars($membershipFee); ?>">

            <h2>Confirm Membership Details</h2>
            <p>Membership Type: <?php echo htmlspecialchars($membershipType); ?></p>
            <p>Membership Fee: <?php echo htmlspecialchars($membershipFee); ?></p>
            
            <a href="../../Billing/Payment/SendRequest.php?billing_id=<?=$billing_id?>&num=<?=$num?>&resident_id=<?=$residentID ?>&CardID=<?=$cardID ?>&amount=<?=$membershipFee?>" class="btn" target="_top">Register</a>
           


            <p style="color:<?php// echo strpos($errorMessage, 'Error') !== false ? 'red' : 'green'; ?>">
                <?php// echo htmlspecialchars($errorMessage); ?>
            </p>
        </form>
    </div>
</body>
</html>
