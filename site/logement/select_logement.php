<?php
session_start();
require 'Connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['nombre_personnes'])) {
    die("Veuillez spécifier un nombre de personnes.");
}

$nombre_personnes = $_SESSION['nombre_personnes'];


if (isset($_POST['point_arret'])) {
    $point_arret_id = $_POST['point_arret'];
    $_SESSION['point_arret_id'] = $point_arret_id;

    $stmt = $conn->prepare("SELECT * FROM river_ride.logements WHERE point_arret_id = :point_arret_id AND capacite >= :capacite AND disponibilite > 0");
    $stmt->bindParam(':point_arret_id', $point_arret_id, PDO::PARAM_INT);
    $stmt->bindParam(':capacite', $nombre_personnes, PDO::PARAM_INT);
    $stmt->execute();

    $logements = $stmt->fetchAll();

    $stmt_name = $conn->prepare("SELECT nom FROM river_ride.points_arret WHERE id = :point_arret_id");
    $stmt_name->bindParam(':point_arret_id', $point_arret_id, PDO::PARAM_INT);
    $stmt_name->execute();

    $point_arret = $stmt_name->fetch(PDO::FETCH_ASSOC);
    $point_arret_name = $point_arret['nom'] ?? '';

if (isset($_POST['select_logement_id'])) {
    $selectedLogementId = $_POST['select_logement_id'];
    $_SESSION['logement_id'] = $selectedLogementId;
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logements disponibles</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            width: 90%;
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .logements-header {
            color: #006400;
            margin-bottom: 20px;
            text-align: center;
        }

        .logement-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border: 1px solid #d1d1d1;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.2s;
            padding: 10px;
        }

        .logement-info:hover {
            transform: translateY(-5px);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .logement-img {
            width: 150px;
            height: 100px;
            object-fit: cover;
        }

        .logement-details {
            flex: 1;
            padding: 10px;
        }

        .logement-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .logement-capacity,
        .logement-price {
            color: #777;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .reserve-button {
            background-color: #006400;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .reserve-button:hover {
            background-color: #005000;
        }

        .button {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #006400;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
        <h2 class="logements-header">Logements disponibles</h2>
        <?php
        if (isset($point_arret_id) && isset($nombre_personnes)) {
            echo "<div class='input-field'>Point d'arrêt : $point_arret_name</div>";
            echo "<div class='input-field'>Nombre de personnes: $nombre_personnes</div>";
        if ($logements) {
            echo "<form method='POST' action='discount_code.php'>";
            foreach ($logements as $logement) {
                echo "<div class='logement-info'>";

                // Si une URL d'image existe pour ce logement, affichez l'image
                if (isset($logement['image_url']) && !empty($logement['image_url'])) {
                    echo "<img src='{$logement['image_url']}' alt='{$logement['nom']}' class='logement-img'>";
                } else {
                    echo "<div class='logement-img'></div>";
                }

                echo "<div class='logement-details'>
                        <div class='logement-name'>{$logement['nom']}</div>
                        <div class='logement-capacity'>Capacité: {$logement['capacite']} personnes</div>
                        <div class='logement-price'>Prix: {$logement['prix_normal']}€</div>
                    </div>
                    <input type='radio' name='select_logement_id' value='{$logement['id']}'> Sélectionner
                </div>";
            }
            echo "<button class='reserve-button' type='submit'>Réserver le logement sélectionné</button>";
            echo "</form>";
        } else {
            echo "<p>Aucun logement disponible pour ce point d'arrêt.</p>";
        }
        } else {
            echo "<p>Une erreur s'est produite. Veuillez réessayer.</p>";
        } 

        ?> 
    </div>
</body>
</html>
