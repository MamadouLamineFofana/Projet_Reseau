<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname_smarttech = "smarttech"; // Base de données smarttech, où se 
trouve la table Clients
$dbname_vmail = "vmail"; // Base de données vmail pour l'envoi des emails
$username = "root"; // Remplacer par l'utilisateur de la base de données
$password = "Misterhood24Kmagic"; // Remplacer par le mot de passe de la 
base de données

// Connexion à la base de données smarttech (pour rechercher l'email)
try {
    $pdo_smarttech = new PDO("mysql:host=$host;dbname=$dbname_smarttech", 
$username, $password);
    $pdo_smarttech->setAttribute(PDO::ATTR_ERRMODE, 
PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Connexion à la base de données vmail (pour mettre à jour le mot de 
passe)
try {
    $pdo_vmail = new PDO("mysql:host=$host;dbname=$dbname_vmail", 
$username, $password);
    $pdo_vmail->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Récupérer l'email du formulaire
    $message = $_POST['message']; // Récupérer le message

    // Requête SQL pour vérifier si l'email est présent dans la table 
Clients de smarttech
    $query = "SELECT email FROM Clients WHERE email = :email";
    $stmt = $pdo_smarttech->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Si l'email est trouvé dans la table Clients
    if ($stmt->rowCount() > 0) {
        // L'email existe, envoyer le message
        // Générer un nouveau mot de passe aléatoire
        $new_password = generateRandomPassword();

        // Hachage du mot de passe avec SSHA512
        $hashed_password = hashPassword($new_password);

        // Mise à jour du mot de passe dans la table mailbox de la base 
vmail
        $update_sql = "UPDATE mailbox SET password = :password WHERE 
username = :username";
        $update_stmt = $pdo_vmail->prepare($update_sql);
        $update_stmt->bindParam(':password', $hashed_password);
        $update_stmt->bindParam(':username', $email);
        $update_stmt->execute();

        // Envoyer l'email avec le nouveau mot de passe
        sendEmail($email, $message, $new_password);

        echo "L'email a été trouvé dans notre base de données. Un nouveau 
mot de passe a été envoyé à " . htmlspecialchars($email);
    } else {
        echo "Cet email n'est pas enregistré dans notre base de données.";
    }
}

// Fonction pour générer un mot de passe aléatoire
function generateRandomPassword() {
    $chars = 
'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    for ($i = 0; $i < 12; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

// Fonction pour hacher le mot de passe avec SSHA512
function hashPassword($password) {
    $salt = uniqid(mt_rand(), true); // Générer un "sel" unique
    return "{SSHA512}" . base64_encode(hash('sha512', $password . $salt, 
true));
}

// Fonction pour envoyer l'email via IredMail
function sendEmail($email, $message, $new_password) {
    $to = $email;
    $subject = "Votre mot de passe a été réinitialisé";
    $body = "Bonjour,\n\nVotre mot de passe a été réinitialisé.\nVoici 
votre nouveau mot de passe : " . $new_password . "\n\nMessage : " . 
$message;
    $headers = "From: postmaster@smarttech.sn";

    // Utilisation de la fonction mail() pour envoyer un email via 
IredMail
    if (mail($to, $subject, $body, $headers)) {
        echo "L'email a été envoyé avec succès à " . 
htmlspecialchars($email) . ".";
    } else {
        echo "L'email n'a pas pu être envoyé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'envoi</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        legend {
            font-weight: bold;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="email"],
        textarea {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <form action="" method="post">
        <fieldset>
            <legend>Envoyer un message</legend>

            <!-- Champ email -->
            <label for="email">Entrez l'email du client :</label>
            <input type="email" id="email" name="email" required />

            <!-- Champ message -->
            <label for="message">Entrez votre message :</label>
            <textarea id="message" name="message" rows="5" 
required></textarea>

            <!-- Bouton d'envoi -->
            <input type="submit" value="Envoyer" />
        </fieldset>
    </form>
</body>
</html>

