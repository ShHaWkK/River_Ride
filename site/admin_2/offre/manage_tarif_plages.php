<?php

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Ajouter une nouvelle plage tarifaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $tarif = $_POST['tarif'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO tarif_plages (start_date, end_date, tarif, description) VALUES (:start_date, :end_date, :tarif, :description)");
    $stmt->bindParam(':start_date', $startDate);
    $stmt->bindParam(':end_date', $endDate);
    $stmt->bindParam(':tarif', $tarif);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    echo "Nouvelle plage tarifaire ajoutée avec succès!";
}

// Récupérer toutes les plages tarifaires pour les afficher
$stmt = $conn->prepare("SELECT * FROM tarif_plages");
$stmt->execute();
$tarifPlages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des plages tarifaires</title>
</head>
<body>
    <h2>Ajouter une nouvelle plage tarifaire</h2>
    <form action="manage_tarif_plages.php" method="post">
        Date de début : <input type="date" name="start_date" required><br><br>
        Date de fin : <input type="date" name="end_date" required><br><br>
        Tarif : <input type="number" name="tarif" step="0.01" required><br><br>
        Description : <textarea name="description"></textarea><br><br>
        <input type="submit" value="Ajouter">
    </form>

    <h2>Plages tarifaires existantes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Date de début</th>
                <th>Date de fin</th>
                <th>Tarif</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tarifPlages as $plage) { ?>
                <tr>
                    <td><?= $plage['start_date'] ?></td>
                    <td><?= $plage['end_date'] ?></td>
                    <td><?= $plage['tarif'] ?> €</td>
                    <td><?= $plage['description'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
