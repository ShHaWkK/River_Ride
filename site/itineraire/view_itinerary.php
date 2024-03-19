<?php
include 'Connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$itineraireDetails = $conn->query("SELECT i.nom AS itineraire_name, pa.nom AS point_arret_name, pa.latitude, pa.longitude FROM itineraire i JOIN points_arret pa ON pa.id = i.start_point_id OR pa.id = i.end_point_id WHERE i.user_id = $user_id")->fetchAll(PDO::FETCH_ASSOC);

if (count($itineraireDetails) < 2) {
    die("Pas suffisamment de données pour afficher l'itinéraire.");
}

$startPoint = $itineraireDetails[0];
$endPoint = $itineraireDetails[1];
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Voir l'itinéraire</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4">Votre itinéraire</h2>
    <div id="map" style="width: 100%; height: 400px;"></div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let map = L.map('map').setView([48.8566, 2.3522], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // Ajout du point de départ
    let startCoords = [<?php echo $startPoint['latitude']; ?>, <?php echo $startPoint['longitude']; ?>];
    L.marker(startCoords).addTo(map).bindPopup("<?php echo htmlspecialchars($startPoint['point_arret_name']); ?>");

    // Ajout du point d'arrivée
    let endCoords = [<?php echo $endPoint['latitude']; ?>, <?php echo $endPoint['longitude']; ?>];
    L.marker(endCoords).addTo(map).bindPopup("<?php echo htmlspecialchars($endPoint['point_arret_name']); ?>");

    L.polyline([startCoords, endCoords], {color: 'blue'}).addTo(map);
    map.fitBounds([startCoords, endCoords]);
});
</script>

</body>
</html>
