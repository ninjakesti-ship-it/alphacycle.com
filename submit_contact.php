<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name    = $_POST['name'];
  $email   = $_POST['email'];
  $subject = $_POST['subject'];
  $message = $_POST['message'];

  // You can save to database or send an email here
  echo "<h2>Thank you, $name! We have received your message.</h2>";
}
?>
