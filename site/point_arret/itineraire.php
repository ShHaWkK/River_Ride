<?php
// itineraire.php
include ('../BDD/db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pointDepartId = $_POST['point_depart'];
    $pointArriveeId = $_POST['point_arrivee'];

    $sql = "SELECT * FROM points_arret WHERE id = :start_id OR id = :end_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':start_id', $pointDepartId, PDO::PARAM_INT);
    $stmt->bindParam(':end_id', $pointArriveeId, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        if ($result['id'] == $pointDepartId) {
            $pointDepart = $result;
        } elseif ($result['id'] == $pointArriveeId) {
            $pointArrivee = $result;
        }
    }

}
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

<div id="map" style="height: 400px; width: 100%;"></div>
<?php
$sql = "SELECT latitude, longitude FROM points_arret WHERE id = :start_id OR id = :end_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':start_id', $pointDepartId, PDO::PARAM_INT);
$stmt->bindParam(':end_id', $pointArriveeId, PDO::PARAM_INT);

$stmt->execute();
$coordinates = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<script>
     var startPoint = <?php echo json_encode($pointDepart); ?>;
    var endPoint = <?php echo json_encode($pointArrivee); ?>;
    var map = L.map('map').setView([48.8566, 2.3522], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    L.Routing.control({
        waypoints: [
            L.latLng(startPoint.latitude, startPoint.longitude),
            L.latLng(endPoint.latitude, endPoint.longitude)
        ],
        routeWhileDragging: true
    }).addTo(map);
</script>
