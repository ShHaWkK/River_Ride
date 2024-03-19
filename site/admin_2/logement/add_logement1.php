<?php
include 'connection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $capacite = $_POST['capacite'];
    $point_arret_id = $_POST['point_arret_id'];
    $prix_normal = $_POST['prix_normal'];
    $prix_promotionnel = $_POST['prix_promotionnel'];

    $sql = "INSERT INTO logements (nom, description, capacite, point_arret_id, prix_normal, prix_promotionnel) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$nom, $description, $capacite, $point_arret_id, $prix_normal, $prix_promotionnel])) {
        $message = "Logement ajouté avec succès!";
    } else {
        $message = "Erreur lors de l'ajout du logement.";
    }
}

$sql_points = "SELECT id, nom FROM points_arret";
$points_arret = $conn->query($sql_points)->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
    <title>Ajouter un logement</title>
</head>
<body>
    <h2>Ajouter un logement</h2>
    <form action="add_logement.php" method="post">
        Nom: <input type="text" name="nom" required>
        Description: <textarea name="description" required></textarea>
        Capacité: <input type="number" name="capacite" required>
        Point d'arrêt:
        <select name="point_arret_id" required>
            <?php foreach ($points_arret as $point) { ?>
                <option value="<?= $point['id'] ?>"><?= $point['nom'] ?></option>
            <?php } ?>
        </select>
        Prix normal: <input type="number" name="prix_normal" step="0.01" required>
        Prix promotionnel: <input type="number" name="prix_promotionnel" step="0.01">
        <input type="submit" value="Ajouter">
    </form>

    <?php if ($message) echo "<p>$message</p>"; ?>
</body>
</html>
<?php
include 'connection.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de PDO pour lancer des exceptions en cas d'erreurs
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Message de succès ou d'erreur
$message = '';

// Obtenez les points d'arrêt de la base de données
$stmt = $conn->prepare("SELECT * FROM points_arret");
$stmt->execute();
$points_arret = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $point_arret_id = $_POST['point_arret_id'];
    // $ferme_de = $_POST['ferme_de'];
    // $ferme_a = $_POST['ferme_a'];
    $prix_normal = $_POST['prix_normal'];
    $prix_promotionnel = $_POST['prix_promotionnel'];
    $description = $_POST['description'];

    // Vérification des données
    if (!is_numeric($prix_normal)) {
        $message = "Prix normal invalide";
    } elseif (!empty($prix_promotionnel) && !is_numeric($prix_promotionnel)) {
        $message = "Prix promotionnel invalide";
    }

    // ... (la même logique pour la validation et le téléchargement de l'image)

    if ($uploadOk == 1) {
        try {
            $stmt = $conn->prepare("INSERT INTO logements (nom, capacite, point_arret_id, ferme_de, ferme_a, prix_normal, prix_promotionnel, description, image_url) 
                                    VALUES (:nom, :capacite, :point_arret_id, :ferme_de, :ferme_a, :prix_normal, :prix_promotionnel, :description, :image_url)");

            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':capacite', $capacite);
            $stmt->bindParam(':point_arret_id', $point_arret_id);
            // $stmt->bindParam(':ferme_de', $ferme_de);
            // $stmt->bindParam(':ferme_a', $ferme_a);
            $stmt->bindParam(':prix_normal', $prix_normal);
            $stmt->bindParam(':prix_promotionnel', $prix_promotionnel);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image_url', $target_file);

            $stmt->execute();

            $message = "Logement ajouté avec succès!";
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du logement: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajout de Logement</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<div class="container">
    <h1>Ajouter un logement</h1>
    <?php if(!empty($message)): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form action="add_logement.php" method="post" enctype="multipart/form-data">
        <div class="input-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" required>
        </div>
        
        <div class="input-group">
            <label>Nom</label>
            <input type="text" name="nom" required>
        </div>

        <div class="input-group">
            <label>Capacité</label>
            <input type="number" name="capacite" required>
        </div>

        <div class="input-group">
            <label>Point d'arrêt</label>
            <select name="point_arret_id" required>
                <?php foreach($points_arret as $point): ?>
                    <option value="<?= htmlspecialchars($point['id']) ?>"><?= htmlspecialchars($point['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <label for="actif">Logement actif :</label>
            <input type="checkbox" name="actif" id="actif" checked>
        </div>

        <div class="input-group">
            <label>Prix normal</label>
            <input type="text" name="prix_normal" required>
        </div>

        <div class="input-group">
            <label>Prix promotionnel</label>
            <input type="text" name="prix_promotionnel">
        </div>

        <div class="input-group">
            <label>Description</label>
            <textarea name="description"></textarea>
        </div>

        <button type="submit">Ajouter</button>
    </form>
</div>

</body>
</html>

        <!-- <div class="input-group">
            <label>Fermé de</label>
            <input type="date" name="ferme_de">
        </div>

        <div class="input-group">
            <label>Fermé à</label>
            <input type="date" name="ferme_a">
        </div> -->
