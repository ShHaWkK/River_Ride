<?php
$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM points_arret";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $points = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Points d'Arrêt</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #cde8d6;
        }

        .registration-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .registration-form h2 {
            text-align: left;
            margin-bottom: 30px;
            color: #138d75;
        }

        /* Styles spécifiques pour la carte Leaflet */
        #map {
            height: 500px;
            width: 100%;
            margin: 20px 0;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="registration-form">
    <h2>Points d'Arrêt</h2>
    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    const points = <?php echo json_encode($points); ?>;
    const center = points[0] ? [points[0].latitude, points[0].longitude] : [48.8566, 2.3522];

    const map = L.map('map').setView(center, 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    points.forEach(point => {
        L.marker([point.latitude, point.longitude])
            .addTo(map)
            .bindPopup(`<strong>${point.nom}</strong><br>${point.description}`);
    });
</script>

</body>
</html>
