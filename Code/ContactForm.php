<?php

if(empty($_POST))
    return;

$name = $_POST["name"];
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$message = $_POST["message"];
$version = phpversion();

$confirmationHeader =
<<<HEADER
MIME-Version: 1.0
Content-type: text/html; charset=utf-8
From: no-reply@marcofaisst.com
Reply-To: no-reply@marcofaisst.com
X-Mailer: PHP$version
HEADER;

$confirmationMailContent =
<<<HTML
    <html>
    <body>
        <div style='padding:50px; font-family:sans-serif'>
            Hallo $name,<br>
            <br>vielen Dank, dass Sie mich kontaktiert haben. Ich werde mich sobald wie möglich bei Ihnen melden.<br>
            <br>Ihre Nachricht an mich war:<br><br>
            <b>Name:</b> $name<br>
            <b>E-Mail:</b> $email<br>
            <b>Nachricht:</b> $message<br><br><br>
            Mit freundlichen Grüßen,<br>Marco Faisst
        </div>
    </body>
    </html>
HTML;

$confirmationMailContent = wordwrap($confirmationMailContent, 70);

const CONFIRMATION_SUBJECT = "Danke für Ihre Nachricht";
$confirmationSentStatus = mail($email, CONFIRMATION_SUBJECT, $confirmationMailContent, $confirmationHeader);

$mainHeader =
<<<HEADER
MIME-Version: 1.0
Content-type: text/html; charset=utf-8
From: kontaktformular@marcofaisst.com
Reply-To: $email
X-Mailer: PHP$version
HEADER;

$mainMailContent =
<<<HTML
    <html>
    <body>
        <div style='padding:50px; font-family:sans-serif'>
            Jemand hat eine Nachricht durch das Kontaktformular auf marcofaisst.com an dich versendet:<br><br>
            <b>Name:</b> $name<br><br>
            <b>E-Mail:</b> $email<br><br>
            <b>Nachricht:</b> $message
        </div>
    </body>
    </html>
HTML;

$mainMailContent = wordwrap($mainMailContent, 70);

const ARTIST_EMAIL = "mojkag@bluemail.ch";
const ARTIST_EMAIL_SUBJECT = "Nachricht vom Kontaktformular deiner Webseite";
$mainSentStatus = mail(ARTIST_EMAIL, ARTIST_EMAIL_SUBJECT, $mainMailContent, $mainHeader);

echo $confirmationSentStatus && $mainSentStatus;

?>