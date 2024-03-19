<?php
include 'connection.php';

// Configuration de PDO pour afficher les erreurs
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Message de succès ou d'erreur
$message = '';

// Obtenez les points d'arrêt de la base de données
$stmt = $conn->prepare("SELECT * FROM points_arret");
$stmt->execute();
$points_arret = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $point_arret_id = $_POST['point_arret_id'];
    $prix_normal = $_POST['prix_normal'];
    $prix_promotionnel = $_POST['prix_promotionnel'];
    $description = $_POST['description'];
    $tarif_nuit = $_POST['tarif_nuit'];
    $prix_adulte = $_POST['prix_adulte'];
    $prix_enfant = $_POST['prix_enfant'];

    // Vérification des données
    if (!is_numeric($prix_normal)) {
        $message = "Prix normal invalide";
    } elseif (!empty($prix_promotionnel) && !is_numeric($prix_promotionnel)) {
        $message = "Prix promotionnel invalide";
    } else {
        // Traitement du téléchargement de l'image
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;

        // Vérifiez si le fichier est une image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $message = "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }

        // Si tout est OK, essayez de télécharger le fichier
        if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            try {
                // Préparez et exécutez l'insertion dans la base de données
                $stmt = $conn->prepare("INSERT INTO logements (nom, capacite, point_arret_id, prix_normal, prix_promotionnel, description, image_url, tarif_nuit, prix_adulte, prix_enfant) 
                                        VALUES (:nom, :capacite, :point_arret_id, :prix_normal, :prix_promotionnel, :description, :image_url, :tarif_nuit, :prix_adulte, :prix_enfant)");

                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':capacite', $capacite);
                $stmt->bindParam(':point_arret_id', $point_arret_id);
                $stmt->bindParam(':prix_normal', $prix_normal);
                $stmt->bindParam(':prix_promotionnel', $prix_promotionnel);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image_url', $target_file);
                $stmt->bindParam(':tarif_nuit', $tarif_nuit);
                $stmt->bindParam(':prix_adulte', $prix_adulte);
                $stmt->bindParam(':prix_enfant', $prix_enfant);

                $stmt->execute();

                $message = "Logement ajouté avec succès!";
            } catch (PDOException $e) {
                $message = "Erreur lors de l'ajout du logement: " . $e->getMessage();
            }
        } else {
            $message = "Il y avait une erreur lors du téléchargement de votre fichier.";
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
<style>
    .container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 30px;
}

.input-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
input[type="number"],
input[type="file"],
textarea,
select {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

</style>
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
            <label>Tarif par nuit</label>
            <input type="text" name="tarif_nuit" required>
        </div>

        <div class="input-group">
            <label>Prix adulte</label>
            <input type="text" name="prix_adulte" required>
        </div>

        <div class="input-group">
            <label>Prix enfant</label>
            <input type="text" name="prix_enfant" required>
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
