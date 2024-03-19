<?php
include 'Connection.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

$pointsArret = $conn->query("SELECT * FROM points_arret")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE HTML>
<html>
<head>
    <title>Composer un itinéraire</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>Choisissez vos points d'arrêt:</h2>

    <form action="choose_accommodation.php" method="post" class="mt-4">
        
        <!-- Liste déroulante pour le point de départ -->
        <div class="form-group">
            <label for="startPoint">Point de départ:</label>
            <select name="startPoint" id="startPoint" class="form-control">
                <?php foreach($pointsArret as $point): ?>
                    <option value="<?php echo $point['id']; ?>"><?php echo htmlspecialchars($point['nom']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Liste déroulante pour le point d'arrivée -->
        <div class="form-group">
            <label for="endPoint">Point d'arrivée:</label>
            <select name="endPoint" id="endPoint" class="form-control">
                <?php foreach($pointsArret as $point): ?>
                    <option value="<?php echo $point['id']; ?>"><?php echo htmlspecialchars($point['nom']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="submit" value="Suivant" class="btn btn-primary mt-3">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
