<?php
include 'Connection.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

$points = $conn->query("SELECT id, nom FROM points_arret")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Composer l'itinéraire</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2 class="mb-4">Composer votre itinéraire</h2>
    <form action="save_itinerary.php" method="post">
        <div class="form-group">
            <label for="start_point_id">Point de départ :</label>
            <select name="start_point_id" id="start_point_id" class="form-control">
                <?php foreach ($points as $point) {
                    echo "<option value=\"{$point['id']}\">{$point['nom']}</option>";
                } ?>
            </select>
        </div>

        <div class="form-group">
            <label for="end_point_id">Point d'arrivée :</label>
            <select name="end_point_id" id="end_point_id" class="form-control">
                <?php foreach ($points as $point) {
                    echo "<option value=\"{$point['id']}\">{$point['nom']}</option>";
                } ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>

</body>
</html>
