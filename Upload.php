<?php
// Configuration FTP
$ftp_server = "192.168.1.120";  // Remplace par ton serveur FTP
$ftp_username = "smarttechuser";
$ftp_password = "Misterhood";
$ftp_directory = "/ftp/upload"; // Dossier distant où stocker les fichiers

$message = "Bienvenue dans le site de dépôt";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"]) && 
isset($_POST["email"])) {
    $email = trim($_POST["email"]);
    $file = $_FILES["file"];

    // Vérifier si le fichier est valide
    if ($file["error"] !== UPLOAD_ERR_OK) {
        $message = "Erreur lors du téléversement.";
    } else {
        // Connexion FTP
        $ftp_conn = ftp_connect($ftp_server);
        if (!$ftp_conn) {
            die("Impossible de se connecter au serveur FTP.");
        }

        // Authentification FTP
        if (!ftp_login($ftp_conn, $ftp_username, $ftp_password)) {
            ftp_close($ftp_conn);
            die("Échec de l'authentification FTP.");
        }

        // Passer en mode passif pour éviter certains blocages
        ftp_pasv($ftp_conn, true);

        // Vérifier si le répertoire existe, sinon le créer
        if (!ftp_chdir($ftp_conn, $ftp_directory)) {
            if (!ftp_mkdir($ftp_conn, $ftp_directory)) {
                $message = "Impossible de créer le répertoire FTP.";
                ftp_close($ftp_conn);
                die($message);
            }
        }

        // Nom du fichier sur le serveur FTP
        $filename = time() . "_" . basename($file["name"]);
        $remote_file = $ftp_directory . "/" . $filename;

        // Téléversement du fichier via FTP
        if (ftp_put($ftp_conn, $remote_file, $file["tmp_name"], 
FTP_BINARY)) {
            $message = "Fichier téléversé avec succès !<br>
                        <a 
href='ftp://$ftp_server$ftp_directory/$filename' 
target='_blank'>Télécharger le fichier</a>";
        } else {
            $message = "Échec du téléversement via FTP.";
        }

        // Fermeture de la connexion FTP
        ftp_close($ftp_conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploader un fichier via FTP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            color: #555;
        }

        input[type="email"],
        input[type="file"],
        button[type="submit"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
            font-size: 14px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .message.success {
            color: green;
        }

        .message.error {
            color: red;
        }

        .message.info {
            color: #333;
        }
    </style>
</head>
<body>

    <form method="POST" enctype="multipart/form-data">
        <h2>Uploader un fichier via FTP</h2>

        <label for="email">Adresse e-mail du client :</label>
        <input type="email" name="email" id="email" placeholder="Entrez 
votre e-mail" required>

        <label for="file">Choisir un fichier :</label>
        <input type="file" name="file" id="file" required>

        <button type="submit">Envoyer</button>
    </form>

    <div class="message <?= isset($message) && strpos($message, 'succès') 
!== false ? 'success' : (isset($message) && strpos($message, 'Erreur') !== 
false ? 'error' : 'info') ?>">
        <?= htmlspecialchars($message) ?>
    </div>

</body>
</html>

