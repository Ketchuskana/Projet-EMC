# Projet EMC - Système de Renouvellement d'Abonnements

Ce projet a été développé pour automatiser le renouvellement des abonnements mensuels sur une plateforme de contenu vidéo. Il inclut des fonctionnalités pour gérer les paiements, envoyer des emails de confirmation aux clients, et générer des factures.

## Structure du Projet
Projet-EMC/ <br>
├── src/ <br>
  │ ├── bd.php # Connexion à la base de données <br>
  │ ├── renouvellementSouscriptions.php # Script principal pour le renouvellement des abonnements <br>
  │ └── PHPMailer/ # Dossier contenant les fichiers de PHPMailer <br>
  │ ├── src/ │ │ ├── Exception.php │ │ ├── PHPMailer.php │ │ └── SMTP.php <br>
├── templates/ <br>
  │ ├── email.html # Modèle d'email envoyé aux clients <br>
  ├ │── styles.css # Fichier CSS pour le style de l'email <br>
  └── README.md # Ce fichier<br>

## Fonctionnalités

- **Renouvellement Automatique** : Parcourt la base de données pour trouver les abonnements à renouveler et met à jour les informations correspondantes.
- **Envoi d'Emails** : Envoie un email de confirmation au client avec les détails de leur abonnement renouvelé.
- **Gestion des TVA** : Calcule la TVA en fonction du pays du client (0% pour hors UE, 21% pour l'UE).
- **Génération de Factures** : Crée un numéro de facture unique pour chaque renouvellement.

## Instructions pour l'Utilisation

1. **Configuration de la Base de Données** : 
   - Modifiez le fichier `bd.php` pour configurer votre connexion à la base de données.

2. **Configurer PHPMailer** :
   - Assurez-vous que PHPMailer est correctement inclus dans votre projet.
   - Dans le fichier `renouvellementSouscritions.php`, modifiez les paramètres d'authentification SMTP pour votre compte Gmail.

3. **Activer l'Accès aux Applications Moins Sécurisées** :
   - Connectez-vous à votre compte Gmail.
   - Allez dans [Mon Compte Google](https://myaccount.google.com/security).
   - Activez "Accès d'applications moins sécurisées".

4. **Tester le Système** :
   - Exécutez le script `renouvellementSouscriptions.php` pour voir si les abonnements sont renouvelés et si les emails sont envoyés correctement.

## Configuration de l'Email

Pour configurer l'envoi d'emails via PHPMailer, modifiez les paramètres suivants dans la fonction `sendEmail` du fichier `renouvellementSouscriptions.php` :

```php
$mail->Username = 'votre_adresse_email@gmail.com'; // Votre adresse email Gmail
$mail->Password = 'votre_mot_de_passe_ou_mot_de_passe_d_application'; // Votre mot de passe ou mot de passe d'application
                    

### Remarques
- N'oubliez pas de remplacer les parties spécifiques à votre projet avec vos propres informations (comme le nom de l'entreprise, les adresses email, etc.).
- Ce fichier README fournit une vue d'ensemble du projet et des instructions claires pour qu'une autre personne puisse le configurer et l'utiliser facilement. 
                    
