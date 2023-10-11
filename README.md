# Projet de Filtres CSV

Ce projet vous permet de télécharger, filtrer et envoyer des fichiers CSV par e-mail. Il est basé sur PHP et utilise Docker avec MailHog pour la gestion des e-mails. Vous pouvez personnaliser les filtres et les options d'envoi d'e-mail selon vos besoins.

## Configuration requise

- Docker (https://www.docker.com/)
- MailHog (https://github.com/mailhog/MailHog)

## Installation

1. Clonez ce dépôt :

   ```bash
   git clone https://github.com/Atmena/03-lab-php.csv.git ./
   ```

2. Démarrez les conteneurs Docker (assurez-vous que Docker est en cours d'exécution) :

   ```bash
   docker-compose up --build -d
   ```

3. Accédez à l'application dans votre navigateur à l'adresse `http://localhost`. Vous pouvez générer des fichiers CSV et les filtrer à l'aide de l'interface.

## Utilisation

1. Générez un fichier CSV : Cliquez sur "Générer un fichier CSV" sur la page d'accueil et suivez les instructions.

2. Appliquez des filtres : Sur la page principale, cochez les filtres souhaités et cliquez sur "Télécharger le CSV" pour filtrer le fichier.

3. Envoyez le fichier par e-mail : Remplissez le formulaire avec l'adresse e-mail du destinataire et cliquez sur "Envoyer par e-mail".

## Configuration de l'envoi d'e-mail

Pour personnaliser la configuration de l'envoi d'e-mail, vous pouvez modifier les paramètres dans `SendMail.php` :

- Adresse e-mail de l'expéditeur
- Sujet de l'e-mail
- Message de l'e-mail

Assurez-vous que votre serveur Docker est correctement configuré pour envoyer des e-mails.

## Auteur

[Atmena](https://github.com/Atmena)