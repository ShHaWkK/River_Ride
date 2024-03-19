<?php
include 'Connection.php';
session_start();

// Assurez-vous que l'ID de l'utilisateur connecté est stocké dans la session sous 'user_id'
if (!isset($_SESSION['user_id'])) {
    die("Vous devez être connecté pour accéder à cette page.");
}

$user_id = $_SESSION['user_id'];

// Récupération des réservations pour cet utilisateur
$stmt = $conn->prepare("SELECT * FROM river_ride.reservations WHERE user_id = ?");
$stmt->execute([$user_id]);

$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Liste des réservations</title>
    <style>
        /* Un style simple pour le tableau */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>Mes réservations</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Date de réservation</th>
        <th>Logement ID</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Nombre de personnes</th>
        <th>Nombre d'adultes</th>
        <th>Nombre d'enfants</th>
        <th>Pack ID</th>
        <th>Statut</th>
    </tr>
    <?php foreach ($reservations as $reservation): ?>
    <tr>
        <td><?= $reservation['id'] ?></td>
        <td><?= $reservation['date_reservation'] ?></td>
        <td><?= $reservation['logement_id'] ?></td>
        <td><?= $reservation['date_debut'] ?></td>
        <td><?= $reservation['date_fin'] ?></td>
        <td><?= $reservation['nombre_personnes'] ?></td>
        <td><?= $reservation['nombre_adultes'] ?></td>
        <td><?= $reservation['nombre_enfants'] ?></td>
        <td><?= $reservation['pack_id'] ?></td>
        <td><?= $reservation['confirme'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
