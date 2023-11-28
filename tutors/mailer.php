<?php

// Load the PHPMailer files manually
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'mailer/Exception.php';
require 'mailer/PHPMailer.php';
require 'mailer/SMTP.php';

// Define a function to send an email using PHPMailer
function send_email($recepient_username, $recepient_email, $subject, $message) {
  // Create a new PHPMailer instance
  $mail = new PHPMailer(true);

  // Set the sender and recipient email addresses
  $sender = 'info@ibumax.online';
  
  try {
    // Server settings
    $mail->SMTPDebug = 0; // Enable verbose debug output
    $mail->isSMTP(); // Send using SMTP
    $mail->Host = 'smtp.titan.email'; // Set the SMTP server to send through
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = $sender; // SMTP username
    $mail->Password = '@AllowMe21'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port = 587; // TCP port to connect to

    // Recipients
    $mail->setFrom($sender, 'Ibumax'); // Set the sender name and email address
    $mail->addAddress($recepient_email, $recepient_username); // Set the recipient name and email address
    $mail->addReplyTo($sender, 'Ibumax'); // Set the reply-to name and email address

    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $subject; // Set the email subject from the input parameter
    $mail->Body = $message;  // Set the HTML message body from the input parameter
    // $mail->AltBody = 'This is the plain text message body for non-HTML mail clients'; // Set the plain text message body

    // Send the email
    $mail->send();
    echo 'Message has been sent';
  } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }
}
