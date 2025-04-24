<?php
require 'config.php';
session_start();
date_default_timezone_set('Asia/Karachi');

// Validate session data
if (!isset($_SESSION['billing_details'])) {
    die('Invalid access');
}

// Retrieve billing details
$billingDetails = $_SESSION['billing_details'];

// JazzCash credentials
$merchantId = $_ENV['MERCHANT_ID'];
$password = $_ENV['PASSWORD'];
$integritySalt = $_ENV['INTEGRITY_SALT'];
$paymentUrl = $_ENV['API_ENDPOINT']; // Sandbox or live URL

// Transaction details
$amount = $_GET['Amount']; 
$amount = $amount * 100;
$txnRefNo = str_pad(mt_rand(1, 999999999), 10, '0', STR_PAD_LEFT);
$billId = $_GET['billing_id'];
$txnDateTime = date('YmdHis');
$txnExpiryDateTime = date('YmdHis', strtotime('+1 hour'));
$returnUrl = 'http://localhost/Project1/DB/Project/Billing/Payment/Response.php';

// Generate Secure Hash
$dataString = $integritySalt . '&' . $amount . '&' . $billId . '&' . $merchantId . '&' . $txnRefNo . '&' . $txnDateTime . '&' . $txnExpiryDateTime . '&' . $returnUrl;
$secureHash = hash_hmac('sha256', $dataString, $integritySalt);
?>

<!D

OCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script>
        function submitForm() {
            document.paymentForm.submit();
        }
    </script>
</head>
<body onload="submitForm()">
<form name="paymentForm" method="post" action="<?php echo htmlspecialchars($paymentUrl); ?>">
    <input type="text" name="pp_Version" value="2.0">
    <input type="text" name="pp_TxnType" value="MPAY">
    <input type="text" name="pp_Language" value="EN">
    <input type="text" name="pp_MerchantID" value="<?php echo $merchantId; ?>">
    <input type="text" name="pp_SubMerchantID" value="">
    <input type="text" name="pp_Password" value="<?php echo $password; ?>">
    <input type="text" name="pp_BankID" value="">
    <input type="text" name="pp_ProductID" value="">
    <input type="text" name="pp_IsRegisteredCustomer" value="Yes">
    <input type="text" name="pp_TokenizedCardNumber" value="">
    <input type="text" name="pp_CustomerID" value="Test">
    <input type="text" name="pp_CustomerEmail" value="symishaimam@gmail.com">
    <input type="text" name="pp_CustomerMobile" value="0343456789">
    <input type="text" name="pp_TxnRefNo" value="<?php echo $txnRefNo; ?>">
    <input type="text" name="pp_Amount" value="<?php echo $amount; ?>">
    <input type="text" name="pp_TxnCurrency" value="PKR">
    <input type="text" name="pp_TxnDateTime" value="<?php echo $txnDateTime; ?>">
    <input type="text" name="pp_BillReference" value="<?php echo $billId; ?>">
    <input type="text" name="pp_Description" value="Billing Payment">
    <input type="text" name="pp_TxnExpiryDateTime" value="<?php echo $txnExpiryDateTime; ?>">
    <input type="text" name="pp_ReturnURL" value="<?php echo $returnUrl; ?>">
    <input type="text" name="pp_SecureHash" value="<?php echo $secureHash; ?>">
    <input type="text" name="ppmpf_1" value="1"> 
    <input type="text" name="ppmpf_2" value="2">
    <input type="text" name="ppmpf_3" value="3">
    <input type="text" name="ppmpf_4" value="4">
    <input type="text" name="ppmpf_5" value="5">
</form>

</body>
</html>
