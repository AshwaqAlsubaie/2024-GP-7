<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to autoload.php of PHPMailer

function sendEmail($to, $subject, $body) {

    $phpmailer = new PHPMailer(true); // Passing true enables exceptions

    try {
        // Server settings
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'tls'; // Enable TLS encryption
        $phpmailer->Port = 587; // TCP port to connect to (TLS)
        $phpmailer->Username = 'neno646192@gmail.com';
        $phpmailer->Password = 'qlgh oixu rtmx uwsc';
        

        // Recipients
        $phpmailer->setFrom('admin@smart-helmet.com', 'Admin');
        $phpmailer->addAddress($to); // Add a recipient

        // Content
        $phpmailer->isHTML(true); // Set email format to HTML
        $phpmailer->Subject = $subject;
        $phpmailer->Body= $body;

        $phpmailer->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $phpmailer->ErrorInfo;
    }
}


?>