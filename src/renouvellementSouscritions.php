<?php
// Inclure la connexion à la base de données
include 'bd.php';  

// Inclure PHPMailer
require 'C:/wamp64/www/Projet-EMC/src/PHPMailer/src/Exception.php';
require 'C:/wamp64/www/Projet-EMC/src/PHPMailer/src/PHPMailer.php';
require 'C:/wamp64/www/Projet-EMC/src/PHPMailer/src/SMTP.php';

// Utilisation des namespaces PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Étape 1 : Récupérer les abonnements à renouveler
$sql = "SELECT * FROM payment WHERE member_expire_on < NOW() AND type = 'month'";
$result = $conn->query($sql);

// Afficher le nombre d'abonnements à renouveler
echo "Nombre d'abonnements à renouveler : " . $result->num_rows . "<br>";

if ($result->num_rows > 0) {
    while ($subscription = $result->fetch_assoc()) {
        // Débogage : Afficher l'ID de l'abonnement et sa date d'expiration
        echo "Renouvellement de l'abonnement ID: " . $subscription['id'] . " | Date d'expiration: " . $subscription['member_expire_on'] . "<br>";

        // Récupérer les informations client pour l'email
        $customer_id = $subscription['id_customer'];
        $customer_info = getCustomerInfo($customer_id, $conn);

        if ($customer_info) { // Vérifiez si le client a été trouvé
            // Étape 2 : Calculer la TVA
            $country = $customer_info['country'];
            $price = $subscription['price'];
            $currency = $subscription['id_currency'];

            // Calcul de la TVA
            $tva_rate = ($country == 'EU') ? 0.21 : 0; // TVA 21% pour l'UE, 0% pour hors UE
            $total_price = $price * (1 + $tva_rate);

            // Étape 3 : Créer une nouvelle commande
            $invoice_number = generateInvoiceNumber($conn);
            $insert_order_sql = "INSERT INTO commande (date_submit, id_customer, tva, TOTAL_HT, TOTAL_TTC, id_currency, num_invoice) VALUES (NOW(), ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_order_sql);
            $tva_value = $tva_rate * $price;

            $stmt->bind_param("iddiss", $customer_id, $tva_value, $price, $total_price, $currency, $invoice_number);
            $stmt->execute();

            // Étape 4 : Mettre à jour la table "payment"
            $update_payment_sql = "UPDATE payment SET member_expire_on = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE id = ?";
            $update_stmt = $conn->prepare($update_payment_sql);
            $update_stmt->bind_param("i", $subscription['id']);
            $update_stmt->execute();

            // Étape 5 : Envoyer un email de confirmation
            $email_content = generateEmailContent($customer_info, $price, $currency);
            sendEmail($customer_info['email'], $email_content);
        } else {
            echo "Client ID: $customer_id non trouvé.<br>";
        }
    }
} else {
    echo "Aucun abonnement à renouveler.<br>"; // Message si aucun abonnement à renouveler
}

$conn->close();

// Fonction pour obtenir les informations client
function getCustomerInfo($customer_id, $conn) {
    $sql = "SELECT * FROM address WHERE id_customer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Fonction pour générer le numéro de facture
function generateInvoiceNumber($conn) {
    $year = date("Y");
    $sql = "SELECT COUNT(*) as count FROM commande WHERE YEAR(date_submit) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['count'] + 1;

    return "EMC" . $year . str_pad($count, 5, "0", STR_PAD_LEFT);
}

// Fonction pour générer le contenu de l'email
function generateEmailContent($customer_info, $price, $currency) {
    $template = file_get_contents('../templates/email.html');
    $template = str_replace('[FIRSTNAME]', $customer_info['firstname'], $template);
    $template = str_replace('[LASTNAME]', $customer_info['lastname'], $template);
    $template = str_replace('[TYPE]', "Membership mensuel " . $price . " " . $currency, $template);
    
    return $template;
}

// Fonction pour envoyer l'email
function sendEmail($to, $email_content) {
    $mail = new PHPMailer(true);
    
    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();                                   // Paramétrer le mail pour utiliser SMTP
        $mail->Host = 'smtp.gmail.com';                    // Serveur SMTP de Gmail
        $mail->SMTPAuth = true;                            // Activez l'authentification SMTP
        $mail->Username = 'ketoutou1@gmail.com';          // Votre adresse email Gmail
        $mail->Password = 'agry kmwr zqoq dnhh';           // Votre mot de passe Gmail ou mot de passe d'application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Activer le chiffrement TLS
        $mail->Port = 587;                                 // Port TCP à utiliser

        // Destinataires
        $mail->setFrom('ketoutou1@gmail.com', 'EMC'); // Votre adresse
        $mail->addAddress($to);                          // Ajouter un destinataire

        // Contenu
        $mail->isHTML(true);                             // Définir le format d'email à HTML
        $mail->Subject = 'Renouvellement de votre abonnement';
        $mail->Body    = $email_content;

        $mail->send();
        echo 'Email envoyé à ' . $to . '<br>'; // Message de confirmation
    } catch (Exception $e) {
        echo "Échec de l'envoi de l'email à $to. Erreur: {$mail->ErrorInfo}<br>";
    }
}
?>