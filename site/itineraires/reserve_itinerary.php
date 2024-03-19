

<?php
include 'Connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

if (isset($_GET['itineraire_id'])) {
    $user_id = $_SESSION['user_id'];
    $itineraire_id = $_GET['itineraire_id'];

    // Mettre à jour la colonne est_reserve dans la table itineraire pour indiquer la réservation
    $stmt = $conn->prepare("UPDATE itineraire SET est_reserve = 1 WHERE id = ?");
    $stmt->execute([$itineraire_id]);

    // // Insérer la réservation dans la table itineraire_reservation
    // $stmt = $conn->prepare("INSERT INTO itineraire_reservation (user_id, itineraire_id, date_reservation) VALUES (?, ?, CURDATE())");
    // $stmt->execute([$user_id, $itineraire_id]);

    header("Location: ../client/itineraire/list_itineraire.php");
    exit;
}
?>
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
