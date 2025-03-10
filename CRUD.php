<?php
// Informations de connexion à la base de données
$host = "localhost";  // Hôte de la base de données
$dbname = "smarttech";  // Nom de la base
$username = "smarttech";  // Nom d'utilisateur
$password = "Misterhood";  // Mot de passe

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", 
$username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Ajout d'un employé
if (isset($_POST['add_employe'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];

    $query = "INSERT INTO Employes (nom, prenom, email) VALUES (:nom, 
:prenom, :email)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nom' => $nom, 'prenom' => $prenom, 'email' => 
$email]);
}

// Ajout d'un client
if (isset($_POST['add_client'])) {
    $nom = $_POST['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];

    $query = "INSERT INTO Clients (nom, adresse, telephone, email) VALUES 
(:nom, :adresse, :telephone, :email)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nom' => $nom, 'adresse' => $adresse, 'telephone' => 
$telephone, 'email' => $email]);
}

// Ajout d'un document
if (isset($_POST['add_document'])) {
    $nom = $_POST['nom'];
    $type = $_POST['type'];
    $date_creation = $_POST['date_creation'];
    $chemin_fichier = $_POST['chemin_fichier'];

    $query = "INSERT INTO Documents (nom, type, date_creation, 
chemin_fichier) VALUES (:nom, :type, :date_creation, :chemin_fichier)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['nom' => $nom, 'type' => $type, 'date_creation' => 
$date_creation, 'chemin_fichier' => $chemin_fichier]);
}

// Suppression d'un employé
if (isset($_GET['delete_employe'])) {
    $id = $_GET['delete_employe'];
    $query = "DELETE FROM Employes WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
}

// Suppression d'un client
if (isset($_GET['delete_client'])) {
    $id = $_GET['delete_client'];
    $query = "DELETE FROM Clients WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
}

// Suppression d'un document
if (isset($_GET['delete_document'])) {
    $id = $_GET['delete_document'];
    $query = "DELETE FROM Documents WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
}

// Récupération des données
$employes = $pdo->query("SELECT * FROM 
Employes")->fetchAll(PDO::FETCH_ASSOC);
$clients = $pdo->query("SELECT * FROM 
Clients")->fetchAll(PDO::FETCH_ASSOC);
$documents = $pdo->query("SELECT * FROM 
Documents")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Employés, Clients & Documents</title>
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Liste des Employés</h2>
<table>
    <tr>
        
<th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Action</th>
    </tr>
    <?php foreach ($employes as $employe): ?>
        <tr>
            <td><?= $employe['id'] ?></td>
            <td><?= $employe['nom'] ?></td>
            <td><?= $employe['prenom'] ?></td>
            <td><?= $employe['email'] ?></td>
            <td><a href="?delete_employe=<?= $employe['id'] 
?>">Supprimer</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Liste des Clients</h2>
<table>
    <tr>
        
<th>ID</th><th>Nom</th><th>Adresse</th><th>Téléphone</th><th>Email</th><th>Action</th>
    </tr>
    <?php foreach ($clients as $client): ?>
        <tr>
            <td><?= $client['id'] ?></td>
            <td><?= $client['nom'] ?></td>
            <td><?= $client['adresse'] ?></td>
            <td><?= $client['telephone'] ?></td>
            <td><?= $client['email'] ?></td>
            <td><a href="?delete_client=<?= $client['id'] 
?>">Supprimer</a></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Liste des Documents</h2>
<table>
    <tr>
        <th>ID</th><th>Nom</th><th>Type</th><th>Date de 
création</th><th>Chemin</th><th>Action</th>
    </tr>
    <?php foreach ($documents as $document): ?>
        <tr>
            <td><?= $document['id'] ?></td>
            <td><?= $document['nom'] ?></td>
            <td><?= $document['type'] ?></td>
            <td><?= $document['date_creation'] ?></td>
            <td><?= $document['chemin_fichier'] ?></td>
            <td><a href="?delete_document=<?= $document['id'] 
?>">Supprimer</a></td>
        </tr>
    <?php endforeach; ?>
</table>
    <!---->
<!-- Formulaires d'ajout -->

<h3>Ajouter un Employé</h3>
<form method="POST" action="">
    <label for="nom">Nom:</label><br>
    <input type="text" id="nom" name="nom" required><br><br>
    <label for="prenom">Prénom:</label><br>
    <input type="text" id="prenom" name="prenom" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <input type="submit" name="add_employe" value="Ajouter">
</form>

<h3>Ajouter un Client</h3>
<form method="POST" action="">
    <label for="nom">Nom:</label><br>
    <input type="text" id="nom" name="nom" required><br><br>
    <label for="adresse">Adresse:</label><br>
    <input type="text" id="adresse" name="adresse" required><br><br>
    <label for="telephone">Téléphone:</label><br>
    <input type="text" id="telephone" name="telephone" required><br><br>
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <input type="submit" name="add_client" value="Ajouter">
</form>

<h3>Ajouter un Document</h3>
<form method="POST" action="">
    <label for="nom">Nom:</label><br>
    <input type="text" id="nom" name="nom" required><br><br>
    <label for="type">Type:</label><br>
    <input type="text" id="type" name="type" required><br><br>
    <label for="date_creation">Date de création:</label><br>
    <input type="date" id="date_creation" name="date_creation" 
required><br><br>
    <label for="chemin_fichier">Chemin du fichier:</label><br>
    <input type="text" id="chemin_fichier" name="chemin_fichier" 
required><br><br>
    <input type="submit" name="add_document" value="Ajouter">
</form>

</body>
</html>

