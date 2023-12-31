<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function sendEmail($recipient, $subject, $htmlBody, $textBody,$logoPath) {
    $mail = new PHPMailer(true); // Passing true enables exceptions

    try {
        //Server settings
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.transip.email'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = $_ENV['SMTP_USER']; // SMTP username
        $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465; // TCP port to connect to

        //Recipients
        $mail->setFrom($_ENV['SMTP_USER'], 'Nerdygatgets');
        $mail->addAddress($recipient); // Add a recipient

        $mail->AddEmbeddedImage($logoPath, 'logo', 'logo.png', 'base64', 'image/png');



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


