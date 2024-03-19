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

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $packId = $_POST['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM packs WHERE id = :pack_id");
        $stmt->bindParam(':pack_id', $packId);
        $stmt->execute();
        $pack = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pack) {
            $message = "Pack non trouvé!";
        } else {
            try {
                $stmt = $conn->prepare("DELETE FROM packs WHERE id = :pack_id");
                $stmt->bindParam(':pack_id', $packId);
                $stmt->execute();

                $message = "Pack supprimé avec succès!";
            } catch (PDOException $e) {
                $message = "Erreur : " . $e->getMessage();
            }
        }
    } catch (PDOException $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Supprimer un Pack</title>
    </head>
<body>
    <div class="container">
        <h1>Supprimer le Pack</h1>
        <div class="message"><?= $message ?></div>
        <?php if ($pack): ?>
            <form method="post">
                <p><strong>Nom du Pack:</strong> <?= htmlspecialchars($pack['nom']) ?></p>
                <p><strong>Date de début:</strong> <?= $pack['date_debut'] ?></p>
                <p><strong>Date de fin:</strong> <?= $pack['date_fin'] ?></p>
                <p><strong>Prix:</strong> <?= $pack['prix'] ?> €</p>
                <p><strong>Description:</strong> <?= htmlspecialchars($pack['description']) ?></p>
                <button type="submit">Confirmer la suppression</button>
            </form>
        <?php else: ?>
            <p>Le pack n'a pas été trouvé.</p>
        <?php endif; ?>
        <a href="list_packs.php">Retour à la liste des packs</a>
    </div>
</body>
</html>