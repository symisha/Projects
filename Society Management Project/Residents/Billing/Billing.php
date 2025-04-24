<?php

include '_dbconnect.php';

require_once("../vendor/autoload.php");
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(_DIR_);
$dotenv->load();

session_start();
date_default_timezone_set('Asia/Karachi');

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];
} else {
    die("Error: No booking ID found.");
}
if (!isset($_SESSION['user_id'])) {
    die("Error: No user session found. Please log in again.");
}
// Validate session data
if (!isset($_SESSION['payment_details'])) {
    die('Invalid access');
}
$paymentDetails = $_SESSION['payment_details'];

// JazzCash credentials
$merchantId = $_ENV['MERCHANT_ID'];
$password = $_ENV['PASSWORD'];
$integritySalt = $_ENV['INTEGRITY_SALT'];
$paymentUrl = $_ENV['API_ENDPOINT']; // Sandbox or live URL

$converted_amount = $paymentDetails['amount'] * 278;

// Transaction details
$amount = $converted_amount * 100;
$txnRefNo = str_pad(mt_rand(1, 999999999), 10, '0', STR_PAD_LEFT);
$paymentId = $paymentDetails['payment_id'];
$txnDateTime = date('YmdHis');
$txnExpiryDateTime = date('YmdHis', strtotime('+1 hour'));

// Include booking_id in the return URL
$returnUrl = 'http://localhost/db_pro/airpro/partials/_getresponse.php?booking_id=' . urlencode($booking_id);

// Generate Secure Hash
$dataString = $integritySalt . '&' . $amount . '&' . $paymentId . '&' . $merchantId . '&' . $txnRefNo . '&' . $txnDateTime . '&' . $txnExpiryDateTime . '&' . $returnUrl;
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
    height: 1400px;
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

.jsformWrapper .formFielWrapper select {
    width: 300px;
    padding: 0.5rem;
    border: 1px solid #ccc;
    float: left;
    font-family: sans-serif;
}

.jsformWrapper .formFielWrapper {
    float: left;
    margin-bottom: 1rem;
}

.jsformWrapper button {
    background: rgba(196, 21, 28, 1);
    border: none;
    color: #fff;
    width: 120px;
    height: 40px;
    line-height: 25px;
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
<script>
function submitForm() {

    CalculateHash();
    var IntegritySalt = document.getElementsByName("salt")[0].value;;
    var hash = CryptoJS.HmacSHA256(document.getElementById("hashValuesString").value, IntegritySalt);
    document.getElementsByName("pp_SecureHash")[0].value = hash + '';

    console.log('string: ' + hashString);
    console.log('hash: ' + document.getElementsByName("pp_SecureHash")[0].value);

    document.jsform.submit();
}
</script>
<script src="https://sandbox.jazzcash.com.pk/Sandbox/Scripts/hmac-sha256.js"></script>

<h3>JazzCash HTTP POST (Page Redirection) Testing</h3>
<div class="jsformWrapper">
    <form name="jsform" method="post"
        action="https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/">

        <!-- For Card Tokenization Version should be 2.0 -->
        <div class="formFielWrapper">
            <label class="active">pp_Version: </label>
            <input type="text" name="pp_Version" value="1.1" readonly="true">
        </div>

        <div class="formFielWrapper">
            <label class="">pp_TxnType: </label>
            <input type="text" name="pp_TxnType" value="MPAY">
        </div>
        
        <div class="formFielWrapper">
            <label class="active">pp_MerchantID: </label>
            <input type="text" name="pp_MerchantID" value="<?php echo $merchantId; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Language: </label>
            <input type="text" name="pp_Language" value="EN">
        </div>

        <div class="formFielWrapper">
            <label class="">pp_SubMerchantID: </label>
            <input type="text" name="pp_SubMerchantID" value="">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Password: </label>
            <input type="text" name="pp_Password" value="<?php echo $password; ?>">
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
            <label class="">pp_DiscountedAmount: </label>
            <input type="text" name="pp_DiscountedAmount" value="">
        </div>

        <div class="formFielWrapper">
            <label class="">pp_DiscountBank: </label>
            <input type="text" name="pp_DiscountBank" value="">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnCurrency: </label>
            <input type="text" name="pp_TxnCurrency" value="PKR">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnDateTime: </label>
            <input type="text" name="pp_TxnDateTime" id="pp_TxnDateTime" value="<?php echo $txnDateTime; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_TxnExpiryDateTime: </label>
            <input type="text" name="pp_TxnExpiryDateTime" id="pp_TxnExpiryDateTime"
                value="<?php echo $txnExpiryDateTime; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_BillReference: </label>
            <input type="text" name="pp_BillReference" value="<?php echo $paymentId; ?>">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_Description: </label>
            <input type="text" name="pp_Description" value="Ticket Bill">
        </div>

        <div class="formFielWrapper">
            <label class="active">pp_ReturnURL: </label>
            <input type="text" name="pp_ReturnURL" value="<?php echo $returnUrl; ?>">
        </div>


        <div class="formFielWrapper">
            <label class="active">pp_SecureHash: </label>
            <input type="text" name="pp_SecureHash" value="">
        </div>

        <div class="formFielWrapper">
            <label class="active">ppmpf 1: </label>
            <input type="text" name="ppmpf_1" value="1">
        </div>

        <div class="formFielWrapper">
            <label class="active">ppmpf 2: </label>
            <input type="text" name="ppmpf_2" value="2">
        </div>

        <div class="formFielWrapper">
            <label class="active">ppmpf 3: </label>
            <input type="text" name="ppmpf_3" value="3">
        </div>

        <div class="formFielWrapper">
            <label class="active">ppmpf 4: </label>
            <input type="text" name="ppmpf_4" value="4">
        </div>

        <div class="formFielWrapper">
            <label class="active">ppmpf 5: </label>
            <input type="text" name="ppmpf_5" value="5">
        </div>

        <button type="button" onclick="submitForm()">Submit</button>

    </form>

    <input type="hidden" name="salt" value="za2sb9c06z">
    <br><br>
    <div class="formFielWrapper" style="margin-bottom: 2rem;">
        <label class="">Hash values string: </label>
        <input type="text" id="hashValuesString" value="">
        <br><br>
    </div>

</div>

<script>
function CalculateHash() {
    var IntegritySalt = document.getElementsByName("salt")[0].value;
    hashString = '';

    hashString += IntegritySalt + '&';

    if (document.getElementsByName("pp_Amount")[0].value != '') {
        hashString += document.getElementsByName("pp_Amount")[0].value + '&';
    }

    if (document.getElementsByName("pp_BillReference")[0].value != '') {
        hashString += document.getElementsByName("pp_BillReference")[0].value + '&';
    }


    if (document.getElementsByName("pp_Description")[0].value != '') {
        hashString += document.getElementsByName("pp_Description")[0].value + '&';
    }
    if (document.getElementsByName("pp_Language")[0].value != '') {
        hashString += document.getElementsByName("pp_Language")[0].value + '&';
    }
    if (document.getElementsByName("pp_MerchantID")[0].value != '') {
        hashString += document.getElementsByName("pp_MerchantID")[0].value + '&';
    }
    if (document.getElementsByName("pp_Password")[0].value != '') {
        hashString += document.getElementsByName("pp_Password")[0].value + '&';
    }
    if (document.getElementsByName("pp_ReturnURL")[0].value != '') {
        hashString += document.getElementsByName("pp_ReturnURL")[0].value + '&';
    }
    if (document.getElementsByName("pp_SubMerchantID")[0].value != '') {
        hashString += document.getElementsByName("pp_SubMerchantID")[0].value + '&';
    }
    if (document.getElementsByName("pp_TxnCurrency")[0].value != '') {
        hashString += document.getElementsByName("pp_TxnCurrency")[0].value + '&';
    }
    if (document.getElementsByName("pp_TxnDateTime")[0].value != '') {
        hashString += document.getElementsByName("pp_TxnDateTime")[0].value + '&';
    }
    if (document.getElementsByName("pp_TxnExpiryDateTime")[0].value != '') {
        hashString += document.getElementsByName("pp_TxnExpiryDateTime")[0].value + '&';
    }
    if (document.getElementsByName("pp_TxnRefNo")[0].value != '') {
        hashString += document.getElementsByName("pp_TxnRefNo")[0].value + '&';
    }

    if (document.getElementsByName("pp_TxnType")[0].value != '') {
        hashString += document.getElementsByName("pp_TxnType")[0].value + '&';
    }

    if (document.getElementsByName("pp_Version")[0].value != '') {
        hashString += document.getElementsByName("pp_Version")[0].value + '&';
    }
    if (document.getElementsByName("ppmpf_1")[0].value != '') {
        hashString += document.getElementsByName("ppmpf_1")[0].value + '&';
    }
    if (document.getElementsByName("ppmpf_2")[0].value != '') {
        hashString += document.getElementsByName("ppmpf_2")[0].value + '&';
    }
    if (document.getElementsByName("ppmpf_3")[0].value != '') {
        hashString += document.getElementsByName("ppmpf_3")[0].value + '&';
    }
    if (document.getElementsByName("ppmpf_4")[0].value != '') {
        hashString += document.getElementsByName("ppmpf_4")[0].value + '&';
    }
    if (document.getElementsByName("ppmpf_5")[0].value != '') {
        hashString += document.getElementsByName("ppmpf_5")[0].value + '&';
    }

    hashString = hashString.slice(0, -1);
    document.getElementById("hashValuesString").value = hashString;
}
</script>