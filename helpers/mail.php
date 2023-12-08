<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\KBS-Webshop\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\KBS-Webshop\vendor\phpmailer\phpmailer\src\SMTP.php';
require 'C:\xampp\htdocs\KBS-Webshop\vendor\phpmailer\phpmailer\src\PHPMailer.php';

function sendEmail($recipient, $subject, $htmlBody, $textBody) {
    $mail = new PHPMailer(true); // Passing true enables exceptions

    try {
        //Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.transip.email'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'samberkhout@renzeboerman.nl'; // SMTP username
        $mail->Password = 'EaglesDeventer#1 '; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to

        //Recipients
        $mail->setFrom('samberkhout@renzeboerman.nl', 'Sam_Berkhout');
        $mail->addAddress($recipient); // Add a recipient

        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $htmlBody;
        $mail->AltBody = $textBody;

        $mail->send();
        return true; // Email sent successfully
    } catch (Exception $e) {
        return 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
    }
}



$recipient = 'sammie.berkhout2006@gmail.com';
$subject = 'Subject of your email';
$htmlBody = 'This is the HTML message body <b>in bold!</b>';
$textBody = 'This is the plain text version of the email content';
if (sendEmail($recipient, $subject, $htmlBody, $textBody)) {
    echo "Email sent successfully";
} else {
    print "mislukt";
}

?>
