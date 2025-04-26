<?php

require ("../../PHPMailer/src/PHPMailer.php");
require ("../../PHPMailer/src/Exception.php");
require ("../../PHPMailer/src/SMTP.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($email, $subject, $body, $Resident_Id = null, $First_Name = null, $attachment = null) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'societyproject08@gmail.com';
        $mail->Password   = '';   // Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('societyproject08@gmail.com', 'Society Management');
        $mail->addAddress($email); 
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        
        // Replace placeholders only if they exist in $body
        if (strpos($body, '{First_Name}') !== false) {
            $body = str_replace('{First_Name}', $First_Name, $body);
        }

        if (strpos($body, '{Resident_Id}') !== false) {
            $body = str_replace('{Resident_Id}', $Resident_Id, $body);
        }
        
        $mail->Body = $body;

        // Add attachment if it exists
        if ($attachment !== null) {
            $mail->addAttachment(realpath($attachment));
        }

        // Send email
        $mail->send();
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Email has been sent to ' . $email . ' 
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
              </div>';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
