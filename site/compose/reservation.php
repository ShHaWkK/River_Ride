<?php 

session_start();
include 'Connection.php'; 
require 'vendor/autoload.php'; // Si vous utilisez Composer pour Stripe et PHPMailer
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}


\Stripe\Stripe::setApiKey('sk_test_51NibFdIp7e1YN1xNrQgpq1L3fGTUWXq0ZGLiFdhKQX8hShChXdVQmnJXqF5NM9LTeYHAPTHrrykNH3XtSXF6NQ2R00CYN4wjqD');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stripeToken'])) {
    $token = $_POST['stripeToken'];
    $amount = $_POST['total_amount'];

    try {
        // Stripe Payment
        $charge = \Stripe\Charge::create([
            'amount' => $amount * 100,  // en centimes
            'currency' => 'eur',
            'description' => 'Paiement pour réservation',
            'source' => $token,
        ]);

        if ($charge->paid) {
            // PHPMailer
            $mail = new PHPMailer(true);
            
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

            // Mail settings
            $mail->SMTPDebug = 0; 
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'riverride573@gmail.com';
            $mail->Password = 'usywmwwlkehtdbbw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('riverride573@gmail.com', 'RiverRide');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de réservation';
            $mail->Body = "<p>Merci $prenom pour votre réservation!</p>
            <p>Voici les détails de votre réservation :</p>
            <p>Date de début: $date_debut</p>
            <p>Nombre de nuits: $nombre_nuits</p>
            <p>Mode de paiement: Carte bancaire</p>
            <p>Prix total: €" . number_format($prix_total, 2) . "</p>";

            $mail->send();
            
            header('Location: confirmation.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Erreur lors du paiement.";
            header('Location: logement.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat de la réservation</title>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logement_id = $_POST['logement_id'];
    $date_debut = $_POST['date_debut'];
    $nombre_nuits = $_POST['nombre_nuits'];

    // Récupérer les informations du logement sélectionné
    $stmt = $conn->prepare("SELECT * FROM logements WHERE id = ?");
    $stmt->execute([$logement_id]);
    $logement = $stmt->fetch(PDO::FETCH_ASSOC);

    $prix_par_nuit = $logement['prix_normal']; // ou prix_promotionnel si une promotion est en cours
    $prix_total = $prix_par_nuit * $nombre_nuits;

    echo "<h3>Informations de réservation</h3>";
    echo "Vous avez choisi le logement : " . htmlspecialchars($logement['nom']) . "<br>";
    echo "Prix par nuit : €" . number_format($prix_par_nuit, 2) . "<br>";
    echo "Nombre de nuits : " . intval($nombre_nuits) . "<br>";
    echo "Prix total : €" . number_format($prix_total, 2) . "<br>";   $mail = new PHPMailer(true);

} else {
    echo "<h3>Erreur lors de la réservation</h3>";
}

?>

</body>
</html>


<a href="logement.php">Retour</a>

</body>
</html>



/<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Lien vers Font Awesome -->
</head>
<body>
    <div class="container">
        <h1>Mode de Paiement</h1>
        <h2>Total à payer: €<?= number_format($prix_total, 2); ?></h2> 
        <?php if(isset($message) && $message): ?>
            <div class="message"><?= $message; ?></div>
        <?php endif; ?>
        
        <form id="payment-form">
            <div class="input-group">
                <label for="cardholder-name"><i class="fas fa-user"></i> Nom du titulaire de la carte :</label>
                <input type="text" id="cardholder-name" required>
            </div>
            <div class="input-group">
                <label for="card-number"><i class="fas fa-credit-card"></i> Numéro de carte :</label>
                <div id="card-element"></div>
            </div>
            <button id="submit-payment"><i class="fas fa-check"></i> Payer</button>
            <div id="card-errors" role="alert"></div>
        </form>
        
        <script src="https://js.stripe.com/v3/"></script>
    </div>
</body>
        <script>
    var stripe = Stripe('pk_test_51N4qGSIBQYgdt4fRRNtBFQ839RFYeyVpcp8qiTY6S10T9JxWhxS3HnpvHdgn1kkE5cTby9SA7y5slsjubauhcnXd0006I2M8El');
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    cardElement.mount('#card-element');

    var cardholderName = document.getElementById('cardholder-name');
    var cardErrors = document.getElementById('card-errors');
    var submitButton = document.getElementById('submit-payment');

    cardElement.addEventListener('change', function(event) {
        if (event.error) {
            cardErrors.textContent = event.error.message;
        } else {
            cardErrors.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        submitButton.disabled = true;
        stripe.createToken(cardElement, { name: cardholderName.value }).then(function(result) {
            submitButton.disabled = false;
            if (result.error) {
                cardErrors.textContent = result.error.message;
            } else {
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
    var form = document.getElementById('payment-form');
    
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    var totalInput = document.createElement('input');
    totalInput.setAttribute('type', 'hidden');
    totalInput.setAttribute('name', 'total_amount');
    totalInput.setAttribute('value', '<?= $prix_total; ?>');
    form.appendChild(totalInput);

    form.action = "conirmation.php"; // dirigez le formulaire vers le fichier PHP
    form.submit();
}


</script>
</html>
    
     