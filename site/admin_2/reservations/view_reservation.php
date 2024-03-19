<?php
include 'Connection.php';
// Requête pour récupérer les réservations avec les détails associés
$sql = "SELECT r.*, u.nom AS user_nom, u.prenom AS user_prenom, l.nom AS logement_nom, p.nom AS point_arret_nom
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN logements l ON r.logement_id = l.id
        JOIN points_arret p ON l.point_arret_id = p.id
        ORDER BY r.date_reservation DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réservations des utilisateurs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
            padding: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h1>Réservations des utilisateurs</h1>

    <table>
        <tr>
            <th>ID Réservation</th>
            <th>Utilisateur</th>
            <th>Date Réservation</th>
            <th>Logement</th>
            <th>Point d'arrêt</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Nombre de personnes</th>
            <th>Nombre d'adultes</th>
            <th>Nombre d'enfants</th>
            <th>Pack ID</th>
            <th>Confirmation</th>
        </tr>
        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_prenom'] . ' ' . $row['user_nom']; ?></td>
            <td><?php echo $row['date_reservation']; ?></td>
            <td><?php echo $row['logement_nom']; ?></td>
            <td><?php echo $row['point_arret_nom']; ?></td>
            <td><?php echo $row['date_debut']; ?></td>
            <td><?php echo $row['date_fin']; ?></td>
            <td><?php echo $row['nombre_personnes']; ?></td>
            <td><?php echo $row['nombre_adultes']; ?></td>
            <td><?php echo $row['nombre_enfants']; ?></td>
            <td><?php echo $row['pack_id']; ?></td>
            <td><?php echo $row['confirme']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
