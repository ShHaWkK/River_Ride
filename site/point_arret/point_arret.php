<!DOCTYPE html>
<html>
<head>
    <title>Carte des itinéraires</title>
    <link rel="stylesheet" type="text/css" href="leaflet.css">
    <script src="leaflet.js"></script>
    <style>
        #map {
            height: 500px;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script>
        var latitude = 47.3625; // Latitude de la Loire
        var longitude = 0.0722; // Longitude de la Loire
        var zoomLevel = 7; // Niveau de zoom initial

        var map = L.map('map').setView([latitude, longitude], zoomLevel);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Ajoutez vos itinéraires et marqueurs de kayak sur la carte ici
        // Utilisez les méthodes de Leaflet pour ajouter des couches, des marqueurs, des polygones, etc.
    </script>
</body>
</html>

    <script>
        // Récupérez les données des itinéraires depuis votre base de données (par exemple, en utilisant PHP et SQL)
        <?php
        include('../BDD/db.php');

        // Crée la connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=river_ride', 'root', 'root');

        // Récupère les itinéraires de la base de données
        $sql = "SELECT * FROM itineraires";
        $stmt = $pdo->query($sql);
        $itineraries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "var map = L.map('map').setView([latitude, longitude], zoomLevel);";
        echo "L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {";
        echo "    attribution: '© OpenStreetMap contributors'";
        echo "}).addTo(map);";

        foreach ($itineraries as $itinerary) {
            // Récupère les points d'arrêt de l'itinéraire
            $sql = "SELECT * FROM itineraires_points_arret WHERE itineraire_id = :itineraireId";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':itineraireId', $itinerary['id']);
            $stmt->execute();
            $points = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "var coordinates = [";
            foreach ($points as $point) {
                echo "    [" . $point['latitude'] . ", " . $point['longitude'] . "],";
            }
            echo "];";

            echo "L.polyline(coordinates).addTo(map);";
        }
        ?>
    </script>
</body>
</html>






<?php
include('../BDD/db.php');

// Créer la connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=river_ride', 'root', 'root');
// Fonction pour créer un nouveau point d'arrêt
function createPointArret($nom, $description) {
    global $pdo;
    $sql = "INSERT INTO points_arret (nom, description) VALUES (:nom, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
}

// Fonction pour créer un nouvel hébergement
function createHebergement($pointArretId, $nom, $description, $capacite, $prix) {
    global $pdo;
    $sql = "INSERT INTO hebergements (point_arret_id, nom, description, capacite, prix) VALUES (:pointArretId, :nom, :description, :capacite, :prix)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':pointArretId', $pointArretId);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':capacite', $capacite);
    $stmt->bindParam(':prix', $prix);
    $stmt->execute();
}

// Fonction pour créer une nouvelle réservation
function createReservation($userId, $dateReservation) {
    global $pdo;
    $sql = "INSERT INTO reservations (user_id, date_reservation) VALUES (:userId, :dateReservation)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':dateReservation', $dateReservation);
    $stmt->execute();
}

// Exemples d'utilisation
createPointArret("Point A", "Description du point A");
createHebergement(1, "Hébergement 1", "Description de l'hébergement 1", 4, 100.00);
createReservation(1, "2023-07-15");

?>