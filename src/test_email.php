<?php
// Inclure les fichiers de PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Utilisation des namespaces PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($to, $email_content) {
    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();                                   // Paramétrer le mail pour utiliser SMTP
        $mail->Host = 'smtp.gmail.com';                    // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;                            // Activez l'authentification SMTP
        $mail->Username = 'ketoutou1@gmail.com';          // Votre adresse email Gmail
        $mail->Password = 'agry kmwr zqoq dnhh';             // Votre mot de passe Gmail ou mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activer le chiffrement TLS
        $mail->Port = 587;                                 // Port TCP à utiliser
        $mail->SMTPDebug = 2;                             // Débogage

        // Destinataires
        $mail->setFrom('ketoutou1@gmail.com', 'EMC'); // Votre adresse
        $mail->addAddress($to);                          // Ajouter un destinataire

        // Contenu
        $mail->isHTML(true);                               // Définir le format d'email à HTML
        $mail->Subject = 'Test d\'envoi d\'email';
        $mail->Body    = $email_content;

        $mail->send();
        echo 'Email envoyé à ' . $to . '<br>'; // Message de confirmation
    } catch (Exception $e) {
        echo "Échec de l'envoi de l'email à $to. Erreur: {$mail->ErrorInfo}<br>";
    }
}

// Test de l'envoi d'email
$email_content = 'Ceci est un test d\'envoi d\'email avec PHPMailer.';
sendEmail('destination_email@example.com', $email_content); // Remplacez par une adresse valide
?>
