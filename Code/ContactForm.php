<?php

if(!empty($_POST))
{
    $name = $_POST["name"];
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $message = $_POST["message"];
    
    $confirmationHeader = 'MIME-Version: 1.0'."\r\n"
        .'Content-type: text/html; charset=utf-8'."\r\n"
        .'From: no-reply@marcofaisst.com'."\r\n"
        .'Reply-To: no-reply@marcofaisst.com'."\r\n"
        .'X-Mailer: PHP'.phpversion();
        
    $confirmationMailContent = "<html><body><div style='padding:50px; font-family:sans-serif'>Hallo $name,<br>
        <br>vielen Dank, dass Sie mich kontaktiert haben. Ich werde mich sobald wie möglich bei Ihnen melden.<br>
        <br>Ihre Nachricht an mich war:<br><br>
        <b>Name:</b> $name<br>
        <b>E-Mail:</b> $email<br>
        <b>Nachricht:</b> $message<br><br><br>
        Mit freundlichen Grüßen,<br>Marco Faisst</div></body></html>";
        
    $confirmationMailContent = wordwrap($confirmationMailContent, 70);
    $confirmationSentStatus = mail($email, "Danke für Ihre Nachricht", $confirmationMailContent, $confirmationHeader);
    
    $mainHeader = 'MIME-Version: 1.0'."\r\n"
        .'Content-type: text/html; charset=utf-8'."\r\n"
        .'From: kontaktformular@marcofaisst.com'."\r\n"
        .'Reply-To: '.$email."\r\n"
        .'X-Mailer: PHP'.phpversion();
        
    $mainMailContent = "<html><body><div style='padding:50px; font-family:sans-serif'>Jemand hat eine Nachricht durch das Kontaktformular auf marcofaisst.com an dich versendet:<br><br>
        <b>Name:</b> $name<br><br>
        <b>E-Mail:</b> $email<br><br>
        <b>Nachricht:</b> $message</div></body></html>";
        
    $mainMailContent = wordwrap($mainMailContent, 70);
    $mainSentStatus = mail("mojkag@bluemail.ch", "Nachricht vom Kontaktformular deiner Webseite", $mainMailContent, $mainHeader);
    
    echo $confirmationSentStatus && $mainSentStatus;
}

?>