<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'yanjirestaurant.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'info@yanjirestaurant.com'; // SMTP username
    $mail->Password = 'INFO_1234yj'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable SSL encryption, TLS also accepted with port 465
    $mail->Port = 465; // TCP port to connect to
    $mail->addAddress('yanjibookings@gmail.com','Yanji Bookings'); // Add a recipient address
    $mail->isHTML(true); // Set email format to HTML
    $month = $_POST['month'];
    $day = $_POST['day'];
    $hour = $_POST['hour'];
    $people = $_POST['people'];
    $message = $_POST['message'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    ////////////RESERVVATION///////////////////////////

    $mail->setFrom($email, 'Reservation');
    $mail->addReplyTo('yanjibookings@gmail.com', 'Yanji Bookings');
    $mail->addReplyTo($email, $email); // Add a recipient address
    $mail->Subject = 'Reservation by ' . $name;
    $mail->Body = 'Please make reservation :<br> ' . '' . $month . '/' . $day . ' at ' . $hour . ' for ' . $people . '(s).<br>';
    $mail->Body = $mail->Body . ' Name: ' . $name . ' ' . $surname . '<br> Phone: ' . $telephone . '<br> Email: ' . $email;
    $mail->Body = $mail->Body . '<br>Additional Message:<br> ' . $message . '<br> - cheers!';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Gosh....it sucks!';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}

////CONFIRMATION

$mail = new PHPMailer(true); // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 2; // Enable verbose debug output
    $mail->isSMTP(); // Set mailer to use SMTP
    $mail->Host = 'yanjirestaurant.com'; // Specify main and backup SMTP servers
    $mail->SMTPAuth = true; // Enable SMTP authentication
    $mail->Username = 'info@yanjirestaurant.com'; // SMTP username
    $mail->Password = 'INFO_1234yj'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable SSL encryption, TLS also accepted with port 465
    $mail->Port = 465; // TCP port to connect to

    $month = $_POST['month'];
    $day = $_POST['day'];
    $hour = $_POST['hour'];
    $people = $_POST['people'];
    $message = $_POST['message'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
  

    $mail->addAddress($email, $name.' '. $surname); // Add a recipient address
    $mail->addCC('yanjibookings@gmail.com'); // Add a recipient address

    $mail->setFrom('yanjibookings@gmail.com', 'Confirmation');
    $mail->addReplyTo('yanjibookings@gmail.com', 'Yanji Bookings');// Add a recipient address

    $mail->isHTML(true); // Set email format to HTML

    $mail->Subject = 'Your reservation at Yanji Restaurant';
    $mail->Body = 'This is an automated email to note that your reservation request for:<br> ' . '' . $month . '/' . $day . ' ' . 'Contact Detail:' . $telephone . ' at ' . $hour . ' for ' . $people . '(s)<br>';
    $mail->Body = $mail->Body . ' at Yanji Restaurant has been received.' ;
    $mail->Body = $mail->Body . '<br>Thanks for your upcoming visit! <br> ' ;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Gosh....it sucks!';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
