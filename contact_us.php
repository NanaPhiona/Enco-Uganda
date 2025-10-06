<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])){
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $subject = htmlspecialchars($_POST["subject"]);
  $message = nl2br(htmlspecialchars($_POST["message"]));

  $mail = new PHPMailer(true);

  try {
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@encouganda.org'; // your Hostinger email
    $mail->Password   = 'EncoUganda1@';       // your Hostinger email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // use PHPMailer::ENCRYPTION_STARTTLS if port 587
    $mail->Port       = 465; // change to 587 if using STARTTLS

        // Sender & recipient
    $mail->setFrom('info@encouganda.org', 'Website Contact Form');
    $mail->addAddress('info@encouganda.org', 'Enco Uganda'); // Always send to your inbox
    $mail->addReplyTo($email, $name); // So
        // Email content
    $mail->isHTML(true);
    $mail->Subject = "New Contact Form Submission from $name";
    $mail->Body    = "
            <h3>New message from your website contact form</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong><br>$message</p>
        ";
    $mail->AltBody = "Name: $name\nEmail: $email\nMessage:\n$message";

    $mail->send();

    $_SESSION['flash_success'] = "✅ Your message has been sent successfully!";

    header("Location: contact_us.php");
    exit();

    } catch (Exception $e) {
      $_SESSION['flash_error'] = "❌ Message could not be sent. Error: {$mail->ErrorInfo}";
      header("Location: contact_us.php");
      exit();
    }

  }


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
    rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <title>enco</title>
</head>

<body>
  <div id="header"></div>
  <section class="main">
    <div class="container mt-5" id="contact-container">
      <h2 class="text-center mb-4">Contact Us</h2>

      <?php if(isset($_SESSION['flash_success'])): ?>
        <div id="flash-message" class="alert alert-success text-center">
          <?= $_SESSION['flash_success']; ?>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
      <?php endif; ?>

      <?php if(isset($_SESSION['flash_error'])): ?>
        <div id="flash-message" class="alert alert-danger text-center">
          <?= $_SESSION['flash_error']; ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
      <?php endif; ?>

      <form action="contact_us.php" method="post">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>

        <div class="form-group">
          <label for="subject">Subject</label>
          <input type="text" class="form-control" id="subject" name="subject" required>
        </div>

        <div class="form-group">
          <label for="message">Message</label>
          <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>

        <button name="submit" type="submit" class="btn btn-primary" id="submit-btn">Submit</button>
      </form>

  </section>

  <div id="footer"></div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script>
    $(function(){
      $("#header").load("header.html");
      $("#footer").load("footer.html");
    });

    document.addEventListener("DOMContentLoaded", function() {
      const flash = document.getElementById("flash-message");
      if (flash) {
        setTimeout(() => {
          flash.style.transition = "opacity 1s ease";
          flash.style.opacity = "0";
          setTimeout(() => flash.remove(), 1000); // remove from DOM after fade
        }, 2000); // 2 seconds
      }
    });


  </script>

</body>

</html>