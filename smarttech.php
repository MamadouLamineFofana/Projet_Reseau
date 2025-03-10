<?php
// Informations de connexion à la base de données
$host = "localhost";
$dbname = "smarttech";
$username = "smarttech";
$password = "Misterhood";

try {
    // Connexion à la base de données avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", 
$username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer l'email d'un utilisateur (exemple : le dernier inscrit)
$query = "SELECT email, nom FROM Clients ORDER BY id DESC LIMIT 1"; // 
Modifier selon ta table
$stmt = $pdo->prepare($query);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si un utilisateur a été trouvé
if ($user) {
    $to = $user['email']; // Email récupéré de la base de données
    $nom = $user['nom'];
    $subject = "Bienvenue sur Smarttech";
    $message = "Bonjour $nom,\n\nVotre compte a été créé avec 
succès.\nBienvenue sur Smarttech !\n\nCordialement,\nL'équipe Smarttech";

    $headers = "From: no-reply@smarttech.local\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Envoyer l'email
    if (mail($to, $subject, $message, $headers)) {
        echo "E-mail envoyé avec succès à $to";
    } else {
        echo "Erreur lors de l'envoi de l'e-mail.";
    }
} else {
    echo "Aucun utilisateur trouvé dans la base de données.";
}
?>

