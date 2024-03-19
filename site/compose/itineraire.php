<?php
include('Connection.php');
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$query = "SELECT * FROM points_arret";
$stmt = $conn->prepare($query);
$stmt->execute();
$arrets = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['arrets'])) {
    $arretsSelectionnes = $_POST['arrets'];
    $itineraire = implode(",", $arretsSelectionnes);
    $userId = $_SESSION['user_id'];

    $query = "INSERT INTO itineraires (user_id, points_arret, date_creation) VALUES (:userId, :itineraire, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":userId", $userId);
    $stmt->bindParam(":itineraire", $itineraire);
    $stmt->execute();

    if (isset($arretsSelectionnes) && $arretsSelectionnes) {
        $query = "SELECT * FROM points_arret WHERE id IN (" . implode(',', array_map('intval', $arretsSelectionnes)) . ")";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $selectedPoints = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'points' => $selectedPoints]);
        exit;
    } else {
        echo json_encode(['success' => false]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Composer un itinéraire</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <style>
        #map {
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<h1>Choisir vos points d'arrêt</h1>
<form id="formArrets" action="save_itineraire.php" method="post">
    <?php foreach ($arrets as $arret): ?>
        <input type="checkbox" name="arrets[]" value="<?php echo $arret['id']; ?>">
        <?php echo $arret['nom']; ?><br>
    <?php endforeach; ?>
    <input type="submit" value="Sauvegarder l'itinéraire">
</form>
<div id="map"></div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var mymap = L.map('map').setView([48.8566, 2.3522], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mymap);

    document.getElementById('formArrets').addEventListener('submit', function (event) {
        event.preventDefault();
        var formData = new FormData(this);

        fetch('itineraire.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.points) {
                mymap.eachLayer(function (layer) {
                    mymap.removeLayer(layer);
                });

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mymap);
                var markers = [];  // Pour stocker les marqueurs

                data.points.forEach(function (point) {
                    var lat = parseFloat(point.latitude);
                    var lng = parseFloat(point.longitude);
                    var marker = L.marker([lat, lng]).addTo(mymap).bindPopup(point.nom);
                    markers.push(marker);  // Ajoutez le marqueur à la liste
                });

                // Tracer une ligne entre les points d'arrêt sélectionnés
                if (markers.length > 1) {
                    var latlngs = markers.map(function(marker) {
                        return marker.getLatLng();
                    });
                    L.polyline(latlngs, {color: 'blue'}).addTo(mymap);
                }
            } else {
                alert("Erreur lors de la mise à jour de l'itinéraire.");
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
</script>
</body>
</html>
