<?php
session_start();
include 'Connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

if (isset($_POST['nombre_personnes'])) {
    $_SESSION['nombre_personnes'] = $_POST['nombre_personnes'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choisir les Dates</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Choisissez vos dates</h2>
        <form action="select_arret.php" method="post" class="mt-3">
            <div class="form-group">
                <label for="date_entree">Date d'entrée:</label>
                <input type="date" name="date_entree" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_sortie">Date de sortie:</label>
                <input type="date" name="date_sortie" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nombre_personnes">Nombre de personnes :</label>
                <input type="number" name="nombre_personnes" class="form-control" required>
            </div>
            <input type="submit" value="Continuer" class="btn btn-primary">
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
