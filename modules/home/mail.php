<?php
$to      = 'info@djandyveltjen.be';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: info@djandyveltjen.be' . "\r\n" .
    'Reply-To: info@djandyveltjen.be' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
?>