<?php
include 'connection.php';
$message = '';

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM logements WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $logement = $stmt->fetch(PDO::FETCH_ASSOC);

    // ... (le code pour récupérer les images et le point d'arrêt reste inchangé)
} else {
    die('Erreur: ID du logement manquant.');
}

$stmt = $conn->prepare("SELECT * FROM points_arret WHERE id = :point_arret_id");
$stmt->bindParam(':point_arret_id', $logement['point_arret_id']);
$stmt->execute();
$current_point_arret = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];
    $ferme_de = $_POST['ferme_de'];
    $ferme_a = $_POST['ferme_a'];
    $prix_normal = $_POST['prix_normal'];
    $prix_promotionnel = $_POST['prix_promo'];
    $tarif_nuit = $_POST['tarif_nuit'];
    $prix_adulte = $_POST['prix_adulte'];
    $prix_enfant = $_POST['prix_enfant'];
    $description = $_POST['description'];
    $actif = isset($_POST['actif']) ? 1 : 0;
    $prix_personne_nuit = $_POST['prix_personne_nuit'];

    // Gérer les dates (ferme_de et ferme_a)
    if (empty($ferme_de)) {
        $ferme_de = null; // Si la date est vide, définir la valeur à NULL
    }
    if (empty($ferme_a)) {
        $ferme_a = null; // Si la date est vide, définir la valeur à NULL
    }
    

    // Vérification des données
    if (!is_numeric($prix_normal) || !is_numeric($prix_promotionnel) || !is_numeric($prix_adulte) || !is_numeric($prix_enfant) || !is_numeric($tarif_nuit) || !is_numeric($prix_personne_nuit)) {
        $message = "Veuillez entrer des valeurs numériques valides.";
    } else {
        try {
            // Mettre à jour les données du logement dans la base de données
            $stmt = $conn->prepare("UPDATE logements 
                                    SET nom = :nom, capacite = :capacite, 
                                    ferme_de = :ferme_de, ferme_a = :ferme_a,
                                    prix_normal = :prix_normal, prix_promotionnel = :prix_promotionnel, 
                                    tarif_nuit = :tarif_nuit, prix_adulte = :prix_adulte, 
                                    prix_enfant = :prix_enfant, description = :description,
                                    actif = :actif, prix_personne_nuit = :prix_personne_nuit
                                    WHERE id = :logement_id");

            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':capacite', $capacite);
            $stmt->bindParam(':ferme_de', $ferme_de);
            $stmt->bindParam(':ferme_a', $ferme_a);
            $stmt->bindParam(':prix_normal', $prix_normal);
            $stmt->bindParam(':prix_promotionnel', $prix_promotionnel);
            $stmt->bindParam(':tarif_nuit', $tarif_nuit);
            $stmt->bindParam(':prix_adulte', $prix_adulte);
            $stmt->bindParam(':prix_enfant', $prix_enfant);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':actif', $actif);
            $stmt->bindParam(':prix_personne_nuit', $prix_personne_nuit);
            $stmt->bindParam(':logement_id', $_GET['id']);

            $stmt->execute();

            $message = "Logement mis à jour avec succès";
        } catch (PDOException $e) {
            $message = "Erreur lors de la mise à jour du logement: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un logement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    
</head>
<body>

<div class="container animate__animated animate__fadeIn">

    <h1>Modifier le logement</h1>

    <div class="input-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

    <?php foreach ($images as $image): ?>
        <div class="image-group">
            <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Image du logement" width="100">
            <a href="delete_image.php?image_id=<?= htmlspecialchars($image['id']) ?>&logement_id=<?= $_GET['id'] ?>">Supprimer</a>
        </div>
    <?php endforeach; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($logement['nom']) ?>">
        </div>

        <div class="form-group">
            <label for="capacite">Capacité:</label>
            <input type="number" id="capacite" name="capacite" value="<?= htmlspecialchars($logement['capacite']) ?>">
        </div>

        <div class="form-group point-arret">
            <label>Point d'Arrêt actuel:</label>
            <span class="point-arret-name"><?= htmlspecialchars($current_point_arret['nom']) ?></span>
        </div>




        <div class="form-group">
            <label for="ferme_de">Fermé De:</label>
            <input type="text" id="ferme_de" name="ferme_de" value="<?= htmlspecialchars($logement['ferme_de']) ?>">
        </div>

        <div class="form-group">
            <label for="ferme_a">Fermé À:</label>
            <input type="text" id="ferme_a" name="ferme_a" value="<?= htmlspecialchars($logement['ferme_a']) ?>">
        </div>

        <div class="form-group">
            <label for="prix_normal">Prix Normal:</label>
            <input type="number" id="prix_normal" name="prix_normal" value="<?= htmlspecialchars($logement['prix_normal']) ?>">
        </div>

        <div class="form-group">
            <label for="prix_promo">Prix Promotionnel:</label>
            <input type="number" id="prix_promo" name="prix_promo" value="<?= htmlspecialchars($logement['prix_promotionnel']) ?>">
        </div>

        <div class="form-group">
            <label for="tarif_nuit">Tarif/Nuit:</label>
            <input type="number" id="tarif_nuit" name="tarif_nuit" value="<?= htmlspecialchars($logement['tarif_nuit']) ?>">
        </div>

        <div class="form-group">
            <label for="prix_adulte">Prix/Adulte:</label>
            <input type="number" id="prix_adulte" name="prix_adulte" value="<?= htmlspecialchars($logement['prix_adulte']) ?>">
        </div>

        <div class="form-group">
            <label for="prix_enfant">Prix/Enfant:</label>
            <input type="number" id="prix_enfant" name="prix_enfant" value="<?= htmlspecialchars($logement['prix_enfant']) ?>">
        </div>



        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?= htmlspecialchars($logement['description']) ?></textarea>
        </div>

       

        <div class="form-group">
            <label>Actif:</label>
            <input type="radio" id="actif_oui" name="actif" value="1" <?= $logement['actif'] ? 'checked' : '' ?>> <label for="actif_oui">Oui</label> <input type="radio" id="actif_non" name="actif" value="0" <?= !$logement['actif'] ? 'checked' : '' ?>> <label for="actif_non">Non</label>
           
        </div>


        <div class="form-group">
            <label for="prix_personne_nuit">Prix/Personne/Nuit:</label>
            <input type="number" id="prix_personne_nuit" name="prix_personne_nuit" value="<?= htmlspecialchars($logement['prix_personne_nuit']) ?>">
        </div>

        <button type="submit">Mettre à jour</button>
    </form>
    <?php if(!empty($message)): ?>
    <div style="color: green; margin-bottom: 20px;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<a href="gestion_logements.php" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; box-shadow: 2px 2px 5px rgba(0,0,0,0.2);">Retour à la liste des logements</a>

</div>


</body>
</html>
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
            max-width: 600px;
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

        .form-group {
            margin-bottom: 1.5em;
            width: 100%;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="number"], button {
            width: 100%;
            padding: 10px;
            border: 1px solid #138d75;
            border-radius: 5px;
        }

        button {
            background-color: #138d75;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
        }

        button:hover {
            background-color: #117e64;
        }
        .form-group.point-arret {
            background-color: #f6f6f6;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }

        .form-group.point-arret:hover {
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group.point-arret label {
            font-weight: 600;
            margin-right: 10px;
        }

        .point-arret-name {
            font-weight: 500;
            font-size: 18px;
            color: #333;
            background: #fff;
            padding: 5px 15px;
            border-radius: 5px;
            display: inline-block;
            border: 1px solid #ddd;
        }
        .image-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 15px;
        }

        .image-group img {
            margin-bottom: 5px;
        }

        /* Styles pour les tablettes */
        @media screen and (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 15px;
            }

            h1 {
                font-size: 1.5em;
            }

            .image-group img {
                width: 50%;
            }
        }
                /* Styles pour les mobiles */
        @media screen and (max-width: 480px) {
            .container {
                max-width: 95%;
                padding: 10px;
            }

            h1 {
                font-size: 1.2em;
                text-align: center;
            }

            .form-group {
                margin-bottom: 1em;
            }

            .image-group img {
                width: 100%;
            }

            label {
                font-size: 0.9em;
            }

            input[type="text"], 
            input[type="number"], 
            button, 
            textarea {
                font-size: 0.9em;
            }

            .point-arret-name {
                font-size: 16px;
                padding: 3px 10px;
            }
        }



    </style>