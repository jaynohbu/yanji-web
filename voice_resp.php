<?php
require_once './vendor/autoload.php';
use Twilio\TwiML\VoiceResponse;

$response = new VoiceResponse();



// Greeting message
$message = "Thank you for calling Yanji Restaurant. "
         . "We are currently unable to take phone bookings or enquiries at this time. "
         . "Please leave a voicemail and we will respond when possible. "
         . "Thank you for your understanding.";

$response->say($message, [
    'voice' => 'Polly.Amy',
    'language' => 'en-GB'
]);



// // OPTIONAL: Play audio after message (you can remove this if not needed)
// $response->play("https://demo.twilio.com/docs/classic.mp3");

// // OPTIONAL: Start recording voicemail
$response->record([
    'maxLength' => 60,       // limit voicemail to 60 seconds
    'playBeep'  => true,
    'finishOnKey' => '#'
]);

$response->hangup();

header('Content-Type: text/xml');
echo $response;
