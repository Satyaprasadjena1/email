<?php

set_time_limit(3600);
require 'vendor/autoload.php';

$emailsPerHour = $_GET["Input"];

$asytsal = 3600 / $emailsPerHour;
$async = $_GET["selectOption"];  // Assuming you want asynchronous sending
$interval = $asytsal;  // Adjust the interval as needed
// Function to send email
function sendEmail($to, $subject, $message, $attachment) {
    global $async, $interval;

    echo $to;

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'youremail@email.com'; // Replace with your Gmail email
    $mail->Password = '**** **** **** ****'; // Replace with your Gmail app-specific password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('youremail@email.com', 'your name'); // Replace with your Gmail email and name
    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body = $message;

    // Add attachment if provided
    if (!empty($attachment)) {
        $mail->addAttachment($attachment);
    }

    if ($mail->send()) {
        echo 'Email sent successfully!</br>';
        if ($async) {
            // Sleep for interval before sending the next email
            sleep($interval);
        }
        echo "<script>console.log('Email sent successfully for $to');</script>";
    } else {
        echo 'Error sending email. </br>' . $mail->ErrorInfo;
    }
}

// Function to move email from one file to another
function moveEmail($email, $sourceFile, $destinationFile) {
     
     $sourceContent = file_get_contents($sourceFile);   // Read the content of the source file

     $sourceContent = str_replace($email, '', $sourceContent);  // Remove the email from the source content
 
     // Write the modified content back to the source file
     file_put_contents($sourceFile, $sourceContent);
 
     // Append the email to the destination file
     file_put_contents($destinationFile, $email . PHP_EOL, FILE_APPEND);
 
     return true; // Return true if the operation is successful
}

// Read email ids from email.txt
$emails = file('email.txt', FILE_IGNORE_NEW_LINES);

// Send emails
foreach ($emails as $email) {
    // Send email
    sendEmail($email, "task review done", "hello", "attachment.txt");
    echo "<script> alert('script 1') </script>";
    // Move email from emails.txt to processed.txt
    moveEmail($email, 'email.txt', 'processed.txt');
    
}

?>