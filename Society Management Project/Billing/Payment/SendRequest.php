<?php

require 'config.php';
require_once("../../vendor/autoload.php");
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();
date_default_timezone_set('Asia/Karachi');

// JazzCash credentials
$merchantId = $_ENV['MERCHANT_ID'];
$password = $_ENV['PASSWORD'];
$integritySalt = $_ENV['INTEGRITY_SALT'];
$paymentUrl = $_ENV['API_ENDPOINT']; // Sandbox or live URL
$resident_id=$_GET['resident_id'];
$num = $_GET['num'];
// Transaction details
$amount = $_GET['amount']; 
if(isset($_GET['CardID']))
$cardID= $_GET['CardID'];
else 
$cardID = null;
$amount = $amount * 100;  // Convert to cents
$txnRefNo = str_pad(mt_rand(1, 999999999), 10, '0', STR_PAD_LEFT);  // Generate unique transaction ref no
$billId = $_GET['billing_id'];
$txnDateTime = date('YmdHis');
$txnExpiryDateTime = date('YmdHis', strtotime('+1 hour'));
$returnUrl = 'http://localhost/Project1/DB/Project/Billing/Payment/Response.php?billing_id=' . urlencode($billId) . '&num=' . urlencode($num) . '&resident_id=' . urlencode($resident_id) . '&cardID=' . urlencode($cardID); 

// Generate Secure Hash
$dataString = $integritySalt . '&' . $amount . '&' . $billId . '&' . $merchantId . '&' . $txnRefNo . '&' . $txnDateTime . '&' . $txnExpiryDateTime . '&' . $returnUrl;
$secureHash = hash_hmac('sha256', $dataString, $integritySalt);
?>

<style>
    body {
        background: #fff;
    }
    form {
        margin: 0;
        padding: 0;
    }
    .jsformWrapper {
        border: 1px solid rgba(196, 21, 28, 0.50);
        padding: 2rem;
        width: 600px;
        margin: 0 auto;
        border-radius: 2px;
        margin-top: 2rem;
        box-shadow: 0 7px 5px #eee;
        padding-bottom: 4rem;
    }
    .jsformWrapper .formFielWrapper label {
        width: 300px;
        float: left;
    }
    .jsformWrapper .formFielWrapper input {
        width: 300px;
        padding: 0.5rem;
        border: 1px solid #ccc;
        float: left;
        font-family: sans-serif;
    }
    .jsformWrapper .formFielWrapper button {
        background: rgba(196, 21, 28, 1);
        border: none;
        color: #fff;
        width: 120px;
        height: 40px;
        font-size: 16px;
        font-family: sans-serif;
        text-transform: uppercase;
        border-radius: 2px;
        cursor: pointer;
    }
    h3 {
        text-align: center;
        margin-top: 3rem;
        color: rgba(196, 21, 28, 1);
    }
</style>

<script src="https://sandbox.jazzcash.com.pk/Sandbox/Scripts/hmac-sha256.js"></script>

<h3>JazzCash HTTP POST (Page Redirection) Testing</h3>
<div class="jsformWrapper">
    <form name="jsform" method="post" action="https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/">
        <div class="formFielWrapper">
            <label class="active">pp_Version: </label>
            <input type="text" name="pp_Version" value="1.1" readonly="true">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnType: </label>
            <input type="text" name="pp_TxnType" value="MPAY">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_MerchantID: </label>
            <input type="text" name="pp_MerchantID" value="<?php echo $merchantId; ?>" readonly="true">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Language: </label>
            <input type="text" name="pp_Language" value="EN">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Password: </label>
            <input type="text" name="pp_Password" value="<?php echo $password; ?>" readonly="true">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnRefNo: </label>
            <input type="text" name="pp_TxnRefNo" id="pp_TxnRefNo" value="<?php echo $txnRefNo; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Amount: </label>
            <input type="text" name="pp_Amount" value="<?php echo $amount; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnCurrency: </label>
            <input type="text" name="pp_TxnCurrency" value="PKR">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnDateTime: </label>
            <input type="text" name="pp_TxnDateTime" value="<?php echo $txnDateTime; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnExpiryDateTime: </label>
            <input type="text" name="pp_TxnExpiryDateTime" value="<?php echo $txnExpiryDateTime; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_BillReference: </label>
            <input type="text" name="pp_BillReference" value="<?php echo $billId; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Description: </label>
            <input type="text" name="pp_Description" value="Maintenance Bill">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_ReturnURL: </label>
            <input type="text" name="pp_ReturnURL" value="<?php echo $returnUrl; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_SecureHash: </label>
            <input type="text" name="pp_SecureHash" value="<?php echo $secureHash; ?>" readonly="true">
        </div>

        <button type="submit">Submit</button>
    </form>
</div>
<script>
    // Function to calculate the secure hash
    function calculateHash() {
        var IntegritySalt = '<?php echo $integritySalt; ?>';
        var hashString = '';

        hashString += IntegritySalt + '&';
        hashString += document.getElementsByName("pp_Amount")[0].value + '&';
        hashString += document.getElementsByName("pp_BillReference")[0].value + '&';
        hashString += document.getElementsByName("pp_Description")[0].value + '&';
        hashString += document.getElementsByName("pp_Language")[0].value + '&';
        hashString += document.getElementsByName("pp_MerchantID")[0].value + '&';
        hashString += document.getElementsByName("pp_Password")[0].value + '&';
        hashString += document.getElementsByName("pp_ReturnURL")[0].value + '&';
        hashString += document.getElementsByName("pp_TxnCurrency")[0].value + '&';
        hashString += document.getElementsByName("pp_TxnDateTime")[0].value + '&';
        hashString += document.getElementsByName("pp_TxnExpiryDateTime")[0].value + '&';
        hashString += document.getElementsByName("pp_TxnRefNo")[0].value;

        // Calculate hash using CryptoJS (HMAC SHA256)
        var hash = CryptoJS.HmacSHA256(hashString, IntegritySalt);
        // Set the hash value to the pp_SecureHash field
        document.getElementsByName("pp_SecureHash")[0].value = hash + '';
    }

    // When the form is about to submit
    function submitFormAutomatically() {
        calculateHash(); // First calculate the hash
        document.forms['jsform'].submit(); // Then submit the form
    }
/*
    // Trigger the submitFormAutomatically function on page load (or you can bind to an event)
    window.onload = function() {
        submitFormAutomatically();  // Automatically submit the form
    };*/
</script>
