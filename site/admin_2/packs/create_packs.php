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

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'] ?? '';
    $itineraire_id = $_POST['itineraire_id'] ?? '';
    $logement_id = $_POST['logement_id'] ?? '';
    $prix = $_POST['prix'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';

    if (empty($nom) || empty($itineraire_id) || empty($prix) || empty($logement_id) || empty($date_debut) || empty($date_fin)) {
        $message = "Veuillez remplir tous les champs requis!";
    } else {
        try {
            $stmt = $conn->prepare("INSERT INTO packs (nom, itineraire_id, logement_id, prix, description, date_debut, date_fin) VALUES (:nom, :itineraire_id, :logement_id, :prix, :description, :date_debut, :date_fin)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':itineraire_id', $itineraire_id);
            $stmt->bindParam(':logement_id', $logement_id);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':date_debut', $date_debut);
            $stmt->bindParam(':date_fin', $date_fin);
            $stmt->execute();

            $message = "Pack créé avec succès!";
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}

function fetchItineraires($conn) {
    $options = '';
    try {
        $stmt = $conn->prepare("SELECT * FROM itineraire");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nom']) . "</option>";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des itinéraires: " . $e->getMessage();
    }
    return $options;
}

function fetchLogements($conn) {
    $options = '';
    try {
        $stmt = $conn->prepare("SELECT * FROM logements WHERE actif = 1");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $options .= "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['nom']) . " - Capacité: " . $row['capacite'] . " personnes - Prix: " . $row['prix_normal'] . "€</option>";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des logements: " . $e->getMessage();
    }
    return $options;
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Créer un Pack</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px 40px;
    background-color: #fff;
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    color: #555;
}

input[type="text"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 10px 15px;
    margin: 5px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    transition: border 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
select:focus {
    border-color: #007BFF;
    outline: none;
    box-shadow: 0px 0px 5px rgba(0, 123, 255, 0.1);
}

button, input[type="submit"] {
    background-color: #007BFF;
    color: white;
    padding: 14px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover, input[type="submit"]:hover {
    background-color: #0056b3;
}

p {
    background-color: #e9ecef;
    padding: 10px;
    border-radius: 5px;
    text-align: center;
}

    </style>
</head>
<body><!DOCTYPE html>
<html>
<head>
    <title>Créer un Pack</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Créer un Pack</h1>
    <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>
    <form action="create_packs.php" method="post">
        <div class="form-group">
            <label for="nom">Nom du Pack:</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <div class="form-group">
            <label for="itineraire_id">Itinéraire :</label>
            <select id="itineraire_id" name="itineraire_id" required>
                <?php echo fetchItineraires($conn); ?>
            </select>
        </div>

        <div class="form-group">
            <label for="logement_id">Logement :</label>
            <select id="logement_id" name="logement_id" required>
                <?php echo fetchLogements($conn); ?>
            </select>
        </div>

        <div class="form-group">
            <label for="prix">Prix du Pack :</label>
            <input type="number" id="prix" name="prix" required>
        </div>

        <div class="form-group">
            <label for="description">Description :</label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="date_debut">Date de début :</label>
            <input type="date" id="date_debut" name="date_debut" required>
        </div>

        <div class="form-group">
            <label for="date_fin">Date de fin :</label>
            <input type="date" id="date_fin" name="date_fin" required>
        </div>

        <input type="submit" value="Créer">
    </form>
</div>
</body>
</html>

