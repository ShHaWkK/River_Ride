<?php

$servername = "localhost";
$dbname = "river_ride"; // Mettez ici le nom de votre base de données
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définit le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connexion échouée : " . $e->getMessage();
}

$stmt = $conn->prepare("SELECT * FROM offres_promotionnelles");
$stmt->execute();
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Offres Promotionnelles</h2>
<table border="1">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Date de Début</th>
            <th>Date de Fin</th>
            <th>Réduction (%)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($offers as $offer) : ?>
            <tr>
                <td><?php echo $offer["nom"]; ?></td>
                <td><?php echo $offer["date_debut"]; ?></td>
                <td><?php echo $offer["date_fin"]; ?></td>
                <td><?php echo $offer["pourcentage_reduction"]; ?></td>
                <td><a href="admin_delete_offer.php?id=<?php echo $offer["id"]; ?>">Supprimer</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>