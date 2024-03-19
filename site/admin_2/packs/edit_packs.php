<?php
session_start();

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Récupération des informations du pack spécifique
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM packs WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$pack = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupération des itinéraires et logements pour la liste déroulante
$stmt = $conn->prepare("SELECT id, nom FROM itineraire");
$stmt->execute();
$itineraires = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare("SELECT id, nom FROM logements");
$stmt->execute();
$logements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Édition des Packs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            font-weight: bold;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
        }

        form label,
        form input,
        form select,
        form button {
            width: 100%;
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="date"],
        form select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        form button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Édition des Packs</h1>
        <form method="post" action="update_pack.php">
            Nom: <input type="text" name="nom" value="<?= $pack['nom'] ?>" required><br>
            Prix: <input type="text" name="prix" value="<?= $pack['prix'] ?>" required><br>
            Date de début: <input type="date" name="date_debut" value="<?= $pack['date_debut'] ?>" required><br>
            Date de fin: <input type="date" name="date_fin" value="<?= $pack['date_fin'] ?>" required><br>
            Itinéraire: 
            <select name="itineraire_id">
                <?php foreach($itineraires as $itineraire): ?>
                    <option value="<?= $itineraire['id'] ?>" <?= $itineraire['id'] == $pack['itineraire_id'] ? 'selected' : '' ?>><?= $itineraire['nom'] ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            Logement: 
            <select name="logement_id">
                <?php foreach($logements as $logement): ?>
                    <option value="<?= $logement['id'] ?>" <?= $logement['id'] == $pack['logement_id'] ? 'selected' : '' ?>><?= $logement['nom'] ?></option>
                <?php endforeach; ?>
            </select>
            <br>
            <input type="hidden" name="id" value="<?= $pack['id'] ?>">
            <button type="submit">Mettre à jour</button>
        </form>
    </div>
</body>
</html>
