<?php
include 'connection.php';

$stmt = $conn->prepare("SELECT * FROM logements");
$stmt->execute();
$logements = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des logements</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #cde8d6;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    max-width: 1200px;
    margin: 2em auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #138d75;
    margin-bottom: 1.5em;
}

table {
    width: 100%;
    border-collapse: collapse;
}

table, th, td {
    border: 1px solid #138d75;
    padding: 8px 12px;
}

th {
    background-color: #138d75;
    color: #fff;
}

tbody tr:hover {
    background-color: #e0f0e9;
}

td img {
    border-radius: 5px;
}

a {
    color: #138d75;
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #138d75;
    border-radius: 5px;
    transition: background-color 0.3s ease-in-out;
}

a:hover {
    background-color: #117e64;
    color: #fff;
}
    
</style>
</head>
<body>
<div class="container">
    <h1>Liste des logements</h1>

    <table border="1" width="100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Capacité</th>
                <th>Point d'Arrêt</th>
                <th>Fermé De</th>
                <th>Fermé À</th>
                <th>Prix Normal</th>
                <th>Prix Promo</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actif</th>
                <th>Tarif/Nuit</th>
                <th>Prix Adulte</th>
                <th>Prix Enfant</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logements as $logement): ?>
                <tr>
                    <td><?= htmlspecialchars($logement['id']) ?></td>
                    <td><?= htmlspecialchars($logement['nom']) ?></td>
                    <td><?= htmlspecialchars($logement['capacite']) ?></td>
                    <td><?= htmlspecialchars($logement['point_arret_id']) ?></td>
                    <td><?= htmlspecialchars($logement['ferme_de']) ?></td>
                    <td><?= htmlspecialchars($logement['ferme_a']) ?></td>
                    <td><?= htmlspecialchars($logement['prix_normal']) ?></td>
                    <td><?= htmlspecialchars($logement['prix_promotionnel']) ?></td>
                    <td><?= htmlspecialchars($logement['description']) ?></td>
                    <td><img src="<?= htmlspecialchars($logement['image_url']) ?>" width="100"></td>
                    <td><?= $logement['actif'] ? 'Oui' : 'Non' ?></td>
                    <td><?= htmlspecialchars($logement['tarif_nuit']) ?></td>
                    <td><?= htmlspecialchars($logement['prix_adulte']) ?></td>
                    <td><?= htmlspecialchars($logement['prix_enfant']) ?></td>
                    <td>
                        <a href="edit_logement.php?id=<?= htmlspecialchars($logement['id']) ?>">Éditer</a>
                        <!-- Ajoutez un lien pour supprimer si nécessaire -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>