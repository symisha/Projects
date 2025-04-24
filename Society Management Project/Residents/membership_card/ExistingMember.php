<?php
session_start();
require_once("../../Includes/config.php");
$resident_id = $_SESSION['Resident_ID'];

// Fetch membership details
$stmt = $conn->prepare("
    SELECT M.Member_ID, M.Status, M.Subscription_Date, MT.Card_ID, MT.Card_Name, MT.Fee
    FROM Memberships M
    JOIN MembershipCards MT ON M.Card_ID = MT.Card_ID
    WHERE M.Resident_ID = ?;
");
$stmt->bind_param("i", $resident_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Membership Management</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: none;
        margin: 0;
        padding: 0;
    }

    /* Main Container */
    .container {
        max-width: 700px;
        margin: 50px auto;
        background: #D9EEEE;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        padding: 20px;
        line-height: 0.8;
        margin-top: 20px
    }

    h2, h3 {
        color: #34838a;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #3498db;
        padding-bottom: 10px;
    }

    /* Membership Details */
    .membership-details {
        background-color: #f9fbfc;
        padding: 20px;
        border: 1px solid #dfe4ea;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .membership-details p {
        font-size: 16px;
        color: #4b4b4b;
    }

    .membership-details strong {
        font-weight: 600;
        color: #34495e;
    }

    /* Status */
    .status {
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .status.active {
        color: #2ecc71;
        background: #eafaf1;
        border: 1px solid #27ae60;
    }

    .status.inactive {
        color: #e74c3c;
        background: #fdecea;
        border: 1px solid #e74c3c;
    }

    /* Form Styling */
    form {
        margin-top: 20px;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
    }

    select {
    width: 100%;
    padding: 8px; /* Reduced padding to make the boxes smaller */
    font-size: 16px; /* Keep font size the same */
    border: 1px solid #ccd1d9;
    border-radius: 5px;
    margin-bottom: 20px;
    background: #ffffff;

    /* Set custom highlight color */
    color: #34495e; /* Text color */
    
    }
    button {
        display: inline-block;
        background-color: #34838a;
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
        width: 100%;
    }

    button:hover {
        background-color: #34838a;
    }

    button.btn-renew {
        background-color: #34838a;
    }

    button.btn-renew:hover {
        background-color: #34838a;
    }
</style>
</head>
<body>
    <?php
    //$active=false;
    if ($result->num_rows > 0) {
        $membership = $result->fetch_assoc();
        //$subscription_start = strtotime($membership['Subscription_Date']);
        //$subscription_end = strtotime("+1 year", $subscription_start);
        //$active = ($subscription_end >= time()) ? true : false;
        $active = ($membership['Status'] == 'Paid') ? true : false;
        ?>
        <!-- Display Membership Card -->
        <div class="container">
    <h2>Membership Details</h2>
    <div class="membership-details">
        <p><strong>Card Name:</strong> <?= $membership['Card_Name']; ?></p>
        <p><strong>Subscription Start:</strong> <?= $membership['Subscription_Date']; ?></p>
        <p><strong>Fee:</strong> <?= $membership['Fee']; ?> PKR</p>
        <p>
            <strong>Status:</strong>
            <span class="status <?= $active ? 'active' : 'inactive'; ?>">
                <?= $active ? "Active" : "Inactive"; ?>
            </span>
        </p>
    </div>

    <?php if ($active) { ?>
    <h3>Upgrade/Downgrade Membership</h3>
    <form action="upgrade_membership.php" method="post">
        <input type="hidden" name="member_id" value="<?= $membership['Member_ID']; ?>">
        <input type="hidden" name="current_card_id" value="<?= $membership['Card_ID']; ?>">
        
        <label for="new_card_id">Select New Membership Card:</label>
        <select id="new_card_id" name="new_card_id" required>
            <?php
            // Fetch other available membership cards
            $card_query = "
                SELECT Card_ID, Card_Name, Fee 
                FROM MembershipCards 
                WHERE Card_ID != ? 
                AND Fee >= 0; -- Example condition for upgrade/downgrade
            ";
            $card_stmt = $conn->prepare($card_query);
            $card_stmt->bind_param("i", $membership['Card_ID']);
            $card_stmt->execute();
            $card_result = $card_stmt->get_result();

            while ($card_row = $card_result->fetch_assoc()) {
                echo "<option value='{$card_row['Card_ID']}'>{$card_row['Card_Name']} - {$card_row['Fee']} PKR</option>";
            }

            $card_stmt->close();
            ?>
        </select>

        <a href="UpdateMembership.php">
    <button type="button" class="btn-renew">Upgrade/Downgrade Membership</button>
</a>

    </form>
<?php } else { ?>
    <h3>Renew/Pay Membership</h3>
    <form action="renew_membership.php" method="post">
        <input type="hidden" name="member_id" value="<?= $membership['Member_ID']; ?>">
        <button type="submit" class="btn-renew">Renew Membership</button>
    </form>
<?php } ?>

</div>
    <?php
    } else {
        header("Location: membership_card.php");
    }

    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
