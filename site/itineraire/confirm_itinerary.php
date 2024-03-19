<?php
include 'Connection.php';
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id']) || !isset($_POST['logements'])) {
    header("Location: compose_itinerary.php");
    exit;
}

$logements = $_POST['logements'];
$itineraireDetails = [];

$stmt = $conn->prepare("SELECT pa.nom as point_arret_name, l.nom as logement_name FROM points_arret pa JOIN logements l ON pa.id = l.point_arret_id WHERE pa.id = ? AND l.id = ?");
foreach ($logements as $point_arret_id => $logement_id) {
    $stmt->execute([$point_arret_id, $logement_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $itineraireDetails[] = $row;
}



?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Confirmer l'itinéraire</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Confirmez votre itinéraire</h2>
    <ul class="list-group mb-4">
        <?php foreach($itineraireDetails as $it): ?>
        <li class="list-group-item"><?php echo htmlspecialchars($it['point_arret_name']) . " - " . htmlspecialchars($it['logement_name']); ?></li>
        <?php endforeach; ?>
    </ul>

    <form action="save_itinerary.php" method="post">
        <?php foreach($logements as $point_arret_id => $logement_id): ?>
            <input type="hidden" name="logements[<?php echo $point_arret_id; ?>]" value="<?php echo $logement_id; ?>">
        <?php endforeach; ?>
        <input type="submit" value="Confirmer" class="btn btn-primary">
    </form>

    <a href="compose_itinerary.php" class="btn btn-secondary mt-3">Modifier l'itinéraire</a>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>