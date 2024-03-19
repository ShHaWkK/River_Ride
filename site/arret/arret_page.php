<?php

include('../BDD/db.php');
include('../includes/header2.php');

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
        body {
        font-family: Arial, sans-serif;
        margin: 0;
        background-color: #cde8d6;
        color: #333;
    }

    .registration-form {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        max-width: 800px;
        margin: 40px auto;
    }

    .registration-form h2 {
        text-align: center;
        color: #138d75;
        border-bottom: 2px solid #138d75;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }

    .point-section {
        border: 1px solid #e1e1e1;
        border-radius: 10px;
        margin-bottom: 30px;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .point-section h3 {
        color: #138d75;
        margin-top: 0;
        border-bottom: 1px solid #e1e1e1;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .point-section p {
        margin-bottom: 20px;
        line-height: 1.6;
    }

    .map-section {
        height: 300px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    .map-section {
        height: 300px;
        margin-bottom: 20px;
        }
    .buttom {   
        text-align: center;
        margin-bottom: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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
    <div class="buttom">
        <a href="../itineraires/compose_itinerary.php" class="btn btn-primary">Composer votre Itinéraire</a>
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
