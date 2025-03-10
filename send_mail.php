<?php
// Paramètres de connexion à la base de données
$host = "localhost";
$dbname = "smarttech";
$username = "smarttech";
$password = "Misterhood";

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, 
$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Vérification si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email']; // Récupérer l'email du formulaire
    $message = $_POST['message']; // Récupérer le message

    // Requête SQL pour vérifier si l'email est présent dans la table 
Clients
    $query = "SELECT nom FROM Clients WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Si l'email est trouvé dans la base de données
    if ($stmt->rowCount() > 0) {
        // L'email existe, envoyer le message
        // Utilisation de mail() pour envoyer le message
        $subject = "Message de votre entreprise";
        $body = "Bonjour,\n\nVoici le message que vous avez demandé :\n\n" 
. htmlspecialchars($message);
        $headers = "From: no-reply@smarttech.com";

        // Envoi de l'email
        if (mail($email, $subject, $body, $headers)) {
            echo "L'email a été trouvé dans notre base de données. Message 
envoyé à " . htmlspecialchars($email) . ".";
        } else {
            echo "Une erreur est survenue lors de l'envoi du message.";
        }
    } else {
        echo "Cet email n'est pas enregistré dans notre base de données.";
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

        form {
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        fieldset {
            border: 1px solid black;
            padding: 10px;
            margin: 0;
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

