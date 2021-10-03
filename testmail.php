<?php

//var_dump($_SERVER);

$to  = 'shlozer@yahoo.fr' ;
   
$subject = 'Essai mail';

$message = 'Une personne est en attente de confirmation<br><br>';




$message .= '<table cellspacing="0" cellpadding="5" border="0">';
$message .= '<tr><td><strong>Nom & Prénom : </strong></td></tr>';
$message .= '<tr><td>Email : </td></tr>';
$message .= '</table><br>';
$message .= 'Pour confirmer l\'inscription de cette personne vous devez vous rendre sur votre<a href="http://ozr-platteforme.webapp-solutions.com" title="Connexion" > espace administrateur</a>';

$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: chelomo.zerbib@sfr.fr'."\r\n".'Reply-to: shlozer@yahoo.fr';

$success = mail($to, $subject, $message, $headers);
$errorMessage = null;
if (!$success) {
    $errorMessage = error_get_last()['message'];
}
echo $errorMessage;
