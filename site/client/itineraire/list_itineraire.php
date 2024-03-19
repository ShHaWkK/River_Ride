<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'Connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT i.*, 
                 pa1.latitude AS start_latitude, pa1.longitude AS start_longitude, 
                 pa2.latitude AS end_latitude, pa2.longitude AS end_longitude
          FROM itineraire i 
          LEFT JOIN points_arret pa1 ON i.start_point_id = pa1.id
          LEFT JOIN points_arret pa2 ON i.end_point_id = pa2.id
          WHERE i.user_id = $user_id AND i.est_reserve = 1";  // Uniquement les itinéraires réservés

$itineraire = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vos Itinéraires Réservés</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        /* Votre CSS ici */
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Vos Itinéraires Réservés</h2>
    <ul>
        <?php foreach ($itineraire as $route) {
            echo "<li>De {$route['start_point_id']} à {$route['end_point_id']}</li>";
        } ?>
    </ul>
    <div id="map" style="width: 100%; height: 450px;"></div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let map = L.map('map').setView([48.8566, 2.3522], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    <?php foreach ($itineraire as $route): ?>
        // Ajout du point de départ
        let startPoint = [<?php echo $route['start_latitude']; ?>, <?php echo $route['start_longitude']; ?>];
        L.marker(startPoint).addTo(map).bindPopup("Départ");

        // Ajout du point d'arrivée
        let endPoint = [<?php echo $route['end_latitude']; ?>, <?php echo $route['end_longitude']; ?>];
        L.marker(endPoint).addTo(map).bindPopup("Arrivée");

        L.polyline([startPoint, endPoint], {color: 'blue'}).addTo(map);
    <?php endforeach; ?>

    // Ajuster la vue pour afficher tous les trajets
    let group = new L.featureGroup([<?php foreach ($itineraire as $route) {
        echo "[{$route['start_latitude']}, {$route['start_longitude']}],";
        echo "[{$route['end_latitude']}, {$route['end_longitude']}],";
    } ?>]);

    map.fitBounds(group.getBounds());
});
</script>
</body>
</html>
