<?php
session_start();
require 'Connection.php';

if (!isset($_SESSION['logement_id'])) {
    die("Erreur : le logement n'a pas été sélectionné.");
}

$logement_id = $_SESSION['logement_id'];

$stmt = $conn->prepare("SELECT * FROM river_ride.logements WHERE id = :logement_id");
$stmt->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
$stmt->execute();

$logement = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$logement) {
    die("Erreur : le logement sélectionné n'est pas trouvé.");
}

// Suppose qu'il y a un prix et un nom pour le logement
$prix = $logement['prix_normal'];
$nom_logement = $logement['nom'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Paiement</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Paiement</h2>
    <p>Vous avez choisi le logement : <?php echo $nom_logement; ?></p>
    <p>Prix total : <?php echo $prix; ?> €</p>
    <form action="traitement_paiement.php" method="post" class="mt-3">
        <!-- Ici, vous pouvez ajouter les champs nécessaires pour la saisie des informations de paiement comme les détails de la carte, etc. -->
        <input type="submit" value="Payer" class="btn btn-primary">
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
