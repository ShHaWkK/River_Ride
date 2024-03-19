<?php
session_start();
include 'Connection.php';
// Enregistrement du nombre de personnes dans la session
if (isset($_POST['nombre_personnes'])) {
    $_SESSION['nombre_personnes'] = $_POST['nombre_personnes'];
    $_SESSION['date_entree'] = $_POST['date_entree'];
    $_SESSION['date_sortie'] = $_POST['date_sortie'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir un Point d'Arrêt</title>
    <link rel="stylesheet" href="style.css">
 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Choisissez un point d'arrêt</h2>
        <form action="select_logement.php" method="post" class="mt-3">
            <div class="form-group">
                <label for="point_arret">Point d'arrêt:</label>
                <select name="point_arret" class="form-control" required>
                    <?php
                    require 'Connection.php';
                    $stmt = $conn->query("SELECT * FROM river_ride.points_arret");
                    while ($row = $stmt->fetch()) {
                        echo "<option value='{$row['id']}'>{$row['nom']}</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="submit" value="Suivant" class="btn btn-primary">
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
