// Créez une instance de la carte
var map = L.map('map').setView([latitude, longitude], zoomLevel);

// Ajoutez une couche de tuiles (par exemple, OpenStreetMap)
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Récupérez les données des itinéraires depuis votre base de données (par exemple, en utilisant PHP et SQL)
<?php
include('../BDD/db.php');

// Crée la connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=river_ride', 'root', 'root');

// Récupère les itinéraires de la base de données
$sql = "SELECT * FROM itineraires";
$stmt = $pdo->query($sql);
$itineraries = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($itineraries as $itinerary) {
    // Récupère les points d'arrêt de l'itinéraire
    $sql = "SELECT * FROM itineraires_points_arret WHERE itineraire_id = :itineraireId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':itineraireId', $itinerary['id']);
    $stmt->execute();
    $points = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Crée un tableau de coordonnées pour les points d'arrêt
    $coordinates = [];
    foreach ($points as $point) {
        $coordinates[] = [$point['latitude'], $point['longitude']];
    }

    // Ajoute l'itinéraire à la carte
    L.polyline($coordinates).addTo(map);
}
?>
