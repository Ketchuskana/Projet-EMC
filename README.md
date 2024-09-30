# Projet EMC - Système de Renouvellement d'Abonnements

Ce projet a été développé pour automatiser le renouvellement des abonnements mensuels sur une plateforme de contenu vidéo. Il inclut des fonctionnalités pour gérer les paiements, envoyer des emails de confirmation aux clients, et générer des factures.

## Structure du Projet
Projet-EMC/ ├── src/ 
                  │ ├── bd.php # Connexion à la base de données 
                  │ ├── renouvellementSouscriptions.php # Script principal pour le renouvellement des abonnements 
                  │ └── PHPMailer/ # Dossier contenant les fichiers de PHPMailer 
                    │ ├── src/ │ │ ├── Exception.php │ │ ├── PHPMailer.php │ │ └── SMTP.php 
                  ├── templates/ 
                    │ └── email.html # Modèle d'email envoyé aux clients 
                    ├── styles.css # Fichier CSS pour le style de l'email 
                    └── README.md # Ce fichier
