<?php
include ('../BDD/db.php');
function getPointsDArret($conn) {
    $stmt = $conn->query('SELECT * FROM points_arret');
    return $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Points d'arrêt</title>
    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">River Ride</a>
        <!-- autres éléments de navigation ici -->
    </nav>
    <div class="container mt-5">
        <h1>Composez votre itinéraire</h1>
        <form action="itineraire.php" method="post">
            <div class="form-group">
                <label for="point_depart">Point de départ :</label>
                <select name="point_depart" class="form-control" id="point_depart">
                    <?php
                    $points = getPointsDArret($conn);
                    foreach ($points as $point) {
                        echo '<option value="' . $point['id'] . '">' . $point['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="point_arrivee">Point d'arrivée :</label>
                <select name="point_arrivee" class="form-control" id="point_arrivee">
                    <?php
                    foreach ($points as $point) {
                        echo '<option value="' . $point['id'] . '">' . $point['nom'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Voir l'itinéraire</button>
        </form>
    </div>
    <!-- JS de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
