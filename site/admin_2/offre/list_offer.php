<?php

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connexion échouée : " . $e->getMessage();
}

$stmt = $conn->prepare("SELECT * FROM offres_promotionnelles");
$stmt->execute();
$offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des Offres</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-top: 50px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 8px 15px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    a, button {
        background-color: #007BFF;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin: 4px 2px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    button[type=submit] {
        background-color: #4CAF50;
    }

    a:hover, button:hover {
        opacity: 0.8;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    input[type=text], input[type=date], input[type=number], select {
        width: 100%;
        padding: 8px 15px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
    }

    input[type=submit] {
        width: 100%;
    }
</style>

</head>
<body>
    <h1>Liste des Offres Promotionnelles</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Réduction (%)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offres as $offre): ?>
                <tr>
                    <td><?= $offre['id'] ?></td>
                    <td><?= $offre['nom'] ?></td>
                    <td><?= $offre['date_debut'] ?></td>
                    <td><?= $offre['date_fin'] ?></td>
                    <td><?= $offre['pourcentage_reduction'] ?></td>
                    <td>
                        <a href="edit_offer.php?id=<?= $offre['id'] ?>">Éditer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
