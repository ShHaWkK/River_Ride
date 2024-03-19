<?php include 'Connection.php'; 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour faire une réservation");
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stripeToken'])) {
        $token = $_POST['stripeToken'];
        $amount = $_POST['total_amount'];
    
        try {
            $charge = \Stripe\Charge::create([
                'amount' => $amount * 100,
                'currency' => 'eur',
                'description' => 'Paiement pour réservation',
                'source' => $token,
            ]);
    
            if ($charge->paid) {
                $logement_id = $_POST['logement_id'];
                $date_debut = $_POST['date_debut'];
                $nombre_nuits = $_POST['nombre_nuits'];
                $email = $_POST['email'];
                $prenom = $_POST['prenom'];
    
                $stmt = $conn->prepare("SELECT * FROM logements WHERE id = ?");
                $stmt->execute([$logement_id]);
                $logement = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if (!$logement) {
                    throw new Exception("Logement non trouvé");
                }
    
                $prix_par_nuit = $logement['prix_normal'];
                $prix_total = $prix_par_nuit * $nombre_nuits;
    
                // Insert into reservations table
                $insertStmt = $conn->prepare("INSERT INTO reservations (user_id, date_reservation, logement_id, date_debut, date_fin, nombre_personnes) VALUES (?, NOW(), ?, ?, ?, ?)");
                $insertStmt->execute([$_SESSION['user_id'], $logement_id, $date_debut, $date_debut, $nombre_nuits + $date_debut, $nombre_nuits]);
    
            } else {
                echo "<h3>Erreur lors du paiement.</h3>";
            }
    
        } catch (\Stripe\Error\Card $e) {
            echo "<h3>Erreur lors du paiement: " . $e->getMessage() . "</h3>";
        } catch (Exception $e) {
            echo "<h3>Erreur : " . $e->getMessage() . "</h3>";
        }
    
    } else {
        echo "<h3>Données du formulaire non reçues correctement</h3>";
    }
    echo "<a href='logement.php'>Retour</a>";
    exit;
}



?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation de logement</title>
</head>
<body>
    <h2>Réservation de logement</h2>
   <?php if (isset($_SESSION['error_message'])) {
    echo "<p>Error: " . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
}?>
    <?php
    $query = $conn->query("SELECT * FROM logements WHERE actif = 1");
    $logements = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <form action="reservation.php" method="post">
        Choisissez un logement : 
        <select name="logement_id" required>
            <?php foreach ($logements as $logement) : ?>
                <option value="<?= $logement['id'] ?>"><?= $logement['nom'] ?> - €<?= $logement['prix_normal'] ?>/nuit</option>
            <?php endforeach; ?>
        </select>
        <br>
        Date de début : <input type="date" name="date_debut" required>
        <br>
        Date de fin : <input type="date" name="date_fin" required>
        <br>
        Nombre de nuits : <input type="number" name="nombre_nuits" min="1" required>
        <br>
        <input type="submit" value="Réserver">
    </form>
</body>
</html>
