<?php
session_start();

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

function fetchPacks($conn) {
    $packs = array();
    try {
        $stmt = $conn->prepare("SELECT p.id, p.nom, p.prix, p.date_debut, p.date_fin, i.nom as itineraire_nom, l.nom as logement_nom 
                                FROM packs p 
                                JOIN itineraire i ON p.itineraire_id = i.id 
                                LEFT JOIN logements l ON p.logement_id = l.id");
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $packs[] = $row;
        }
    } catch (PDOException $e) {
        echo "Erreur lors de la récupération des packs: " . $e->getMessage();
    }
    return $packs;
}

$packs = fetchPacks($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Liste des Packs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #FF5733;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #FF2E00;
        }

    </style>
</head>
<body>
    <div class="container">
        <h1>Liste des Packs</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Itinéraire</th>
                    <th>Logement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packs as $pack): ?>
                    <tr>
                        <td><?= $pack['id'] ?></td>
                        <td><?= htmlspecialchars($pack['nom']) ?></td>
                        <td><?= htmlspecialchars($pack['prix']) ?>€</td>
                        <td><?= $pack['date_debut'] ?></td>
                        <td><?= $pack['date_fin'] ?></td>
                        <td><?= htmlspecialchars($pack['itineraire_nom']) ?></td>
                        <td><?= htmlspecialchars($pack['logement_nom']) ?></td>
                        <td>
                            <!-- Bouton d'édition -->
                            <a href="edit_packs.php?id=<?= $pack['id'] ?>">Éditer</a>
                            &nbsp;
                            <!-- Bouton de suppression -->
                            <form method="post" action="delete_packs.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $pack['id'] ?>">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
