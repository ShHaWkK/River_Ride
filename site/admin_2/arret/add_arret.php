<?php

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

// Connectez-vous à la base de données en utilisant PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifiez si les champs requis sont définis et non vides
    if(isset($_POST['nom'], $_POST['latitude'], $_POST['longitude']) && !empty($_POST['nom']) && !empty($_POST['latitude']) && !empty($_POST['longitude'])) {
        
        $nom = $_POST['nom'];
        $description = isset($_POST['description']) ? $_POST['description'] : null;
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];

        // Préparez et exécutez la requête d'insertion
        $stmt = $conn->prepare("INSERT INTO points_arret (nom, description, latitude, longitude) VALUES (:nom, :description, :latitude, :longitude)");
        
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);

        if($stmt->execute()) {
            echo "Point d'arrêt ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du point d'arrêt.";
        }
    } else {
        echo "Veuillez fournir les informations requises (nom, latitude, longitude).";
    }

} catch(PDOException $e) {
    echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
} finally {
    $conn = null;  // Fermer la connexion
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Point d'Arrêt</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f5f5f5;
    padding: 20px;
}

.container {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    max-width: 500px;
    margin: 50px auto;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.input-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="text"],
input[type="number"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 16px;
}

button {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

button:hover {
    background-color: #0056b3;
}

    </style>
</head>

<body>
    <div class="container">
        <h2>Ajouter un Point d'Arrêt</h2>
        <form action="add_arret.php" method="post">
            <div class="input-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="input-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <div class="input-group">
                <label for="latitude">Latitude:</label>
                <input type="number" id="latitude" name="latitude" step="0.00000001" required>
            </div>
            <div class="input-group">
                <label for="longitude">Longitude:</label>
                <input type="number" id="longitude" name="longitude" step="0.00000001" required>
            </div>
            <div class="input-group">
                <button type="submit">Ajouter</button>
            </div>
        </form>
    </div>
</body>

</html>
