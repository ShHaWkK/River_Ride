<?php

include('../Connection.php');
include('../../includes/header2.php');

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM points_arret";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $points = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Points d'arrêt</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <style>
        /* ... [your provided styles] ... */
        .map-section {
            height: 300px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Points d'arrêt du Fleuve</h2>

        <?php foreach($points as $point): ?>
            <div class="point-section">
                <h3><?php echo $point['nom']; ?></h3>
                <p><?php echo $point['description']; ?></p>
                <div id="map-<?php echo $point['id']; ?>" class="map-section"></div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        <?php foreach($points as $point): ?>
            const map<?php echo $point['id']; ?> = L.map('map-<?php echo $point['id']; ?>').setView([<?php echo $point['latitude']; ?>, <?php echo $point['longitude']; ?>], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map<?php echo $point['id']; ?>);

            L.marker([<?php echo $point['latitude']; ?>, <?php echo $point['longitude']; ?>]).addTo(map<?php echo $point['id']; ?>);
        <?php endforeach; ?>
    </script>
</body>
</html>
