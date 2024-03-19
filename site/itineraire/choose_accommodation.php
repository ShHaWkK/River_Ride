<?php
include 'Connection.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['startPoint']) || !isset($_POST['endPoint'])) {
    header("Location: composer_itinerary.php");
    exit;
}

$startPoint = $_POST['startPoint'];
$endPoint = $_POST['endPoint'];

$stmt = $conn->prepare("SELECT l.*, pa.nom AS point_arret_name FROM logements l JOIN points_arret pa ON l.point_arret_id = pa.id WHERE l.point_arret_id IN (?, ?)");
$stmt->execute([$startPoint, $endPoint]);
$logements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Choisir un logement</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Choisissez un logement pour chaque point d'arrêt:</h2>

    <form action="save_itinerary.php" method="post" class="mt-4">
        <?php foreach($logements as $logement): ?>
        <div class="form-group">
            <h4><?php echo htmlspecialchars($logement['point_arret_name']); ?></h4>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="logements[<?php echo $logement['point_arret_id']; ?>]" value="<?php echo $logement['id']; ?>" id="logement_<?php echo $logement['id']; ?>">
                <label class="form-check-label" for="logement_<?php echo $logement['id']; ?>">
                    <?php echo htmlspecialchars($logement['nom']); ?>
                </label>
            </div>
        </div>
        <?php endforeach; ?>

        <input type="submit" value="Confirmer" class="btn btn-primary">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
