<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de la réservation</title>
    </head>
<body>
    <h1>Confirmation de la réservation</h1>
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    div class="container">
    <h1>Confirmation</h1>

    <?php
    // Afficher le message de succès s'il existe
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);  // Effacer le message après l'affichage
    }
    ?>

    <p>Merci pour votre paiement ! Votre transaction a été traitée avec succès.</p>
    <a href="index.php">Retour à l'accueil</a>
</div>
</body>
</html>
<?php
include ('Connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $insertStmt = $conn->prepare("INSERT INTO reservations (user_id, date_reservation, logement_id, date_debut, date_fin, nombre_personnes) VALUES (?, NOW(), ?, ?, ?, ?)");
    $insertStmt->execute([$_SESSION['user_id'], $logement_id, $date_debut, $date_debut, $nombre_nuits + $date_debut, $nombre_nuits]);
    $message = "Votre réservation a été confirmée. Vous allez recevoir un email de confirmation.";
    $mail = new PHPMailer(true);
    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'riverride573@gmail.com';
        $mail->Password = 'usywmwwlkehtdbbw';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        
        $mail->setFrom('riverride573@gmail.com', 'RiverRide');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Confirmation de réservation';

        $mail->Body = "<p>Bonjour $prenom,</p>
        <p>Merci pour votre réservation!</p>
        <p>Voici les détails de votre réservation :</p>
        <p>Date de début: $date_debut</p>
        <p>Nombre de nuits: $nombre_nuits</p>
        <p>Mode de paiement: Carte bancaire</p>
        <p>Prix total: €" . number_format($prix_total, 2) . "</p>";

        $mail->send();
        echo "<h3>Email de confirmation envoyé</h3>";
    } catch (Exception $e) {
        echo "<h3>Erreur lors de l'envoi de l'email: " . $mail->ErrorInfo . "</h3>";
    }
} else {
    echo "<h3>Erreur lors de la réservation</h3>";
}
