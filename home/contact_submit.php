<?php
session_start();
require_once '../admin/database.php'; // Include your database connection file

// Include the PHPMailer files
require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate form data
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $message = filter_var(trim($_POST["message"]), FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Store submission in the database
    $query = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $name, $email, $message);
    
    if ($stmt->execute()) {
        // Send an email notification using PHPMailer
        $mail = new PHPMailer(true);

       // Enable PHPMailer debugging
$mail->SMTPDebug = 2; // Set to 2 for detailed output (can use 3 for even more details)
$mail->Debugoutput = 'html'; // Output debug information in HTML format

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'zeuspitstop24@gmail.com'; // Your Gmail address
    $mail->Password   = 'nsvc bbyn kvxa zcll'; // Your Gmail password or app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    //Recipients
    $mail->setFrom('no-reply@zeuspitstop.com', 'Zeus Pitstop');
    $mail->addAddress('zeuspitstop24@gmail.com'); // Your email address

    // Content
    $mail->isHTML(false);
    $mail->Subject = 'New Contact Form Submission';
    $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $mail->send();
    echo "<script>alert('Thank you for contacting us. We will get back to you soon.'); window.location.href='contact.php';</script>";
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


    } else {
        // Error inserting into the database
        echo "<script>alert('Sorry, there was an issue submitting your message. Please try again later.'); window.location.href='contact.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
