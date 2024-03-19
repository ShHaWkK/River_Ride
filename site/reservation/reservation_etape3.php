<?php
session_start();
include 'Connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

$message = "";

if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour faire une réservation");
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}
$reservation_id = null;
if (isset($_POST['reservation_id']) && !empty($_POST['reservation_id'])) {
    $reservation_id = intval($_POST['reservation_id']);
} elseif (isset($_GET['reservation_id']) && !empty($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
} else {
    die("ID de réservation non fourni");
}



$code_reduction = isset($_POST['code_reduction']) ? $_POST['code_reduction'] : "";

// Vérifier si le code de réduction est valide
$discountPercentage = 0;
if (!empty($code_reduction)) {
    $stmtCheckCode = $conn->prepare("SELECT pourcentage_reduction FROM codes_reduction WHERE code = :code");
    $stmtCheckCode->bindParam(':code', $code_reduction, PDO::PARAM_STR);
    $stmtCheckCode->execute();
    $discountPercentage = $stmtCheckCode->fetch(PDO::FETCH_ASSOC)['pourcentage_reduction'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mode_paiement = isset($_POST['mode_paiement']) ? $_POST['mode_paiement'] : "";

    // Enregistrer le mode de paiement dans la base de données
    try {
        $stmtUpdateReservation = $conn->prepare("UPDATE reservations SET confirme = 'confirmé' WHERE id = :reservation_id");
        $stmtUpdateReservation->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);

        if ($stmtUpdateReservation->execute()) {
            // Rediriger vers la page de confirmation
            header("Location: confirmation.php?reservation_id=" . $reservation_id);
            exit;
        } else {
            $message = "Erreur lors de la confirmation de la réservation";
        }
    } catch (PDOException $e) {
        $message = "Erreur lors de la confirmation de la réservation: " . $e->getMessage();
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réserver un Pack - Étape 3</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3faf3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #cde8d6;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #2e5d34;
        }

        .reserve-btn {
            background-color: #2e5d34;
            color: #cde8d6;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .reserve-btn:hover {
            background-color: #24472b;
        }

        .message {
            background-color: #f9c9c3;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
            color: #a82515;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Réserver un Pack - Étape 3</h1>
    <?php if(isset($message) && $message): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>">

        <div class="container">
        <h1>Mode de Paiement</h1>
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

        // Ajouter une redirection vers send_confirmation_email.php après le succès du paiement
        window.location.href = 'send_confirmation_email.php';
 // Ajouter une redirection vers reservation_etape4.php après le succès du paiement
        window.location.href = 'reservation_etape4.php';


        // Soumettre le formulaire (vous pouvez supprimer cette ligne si la redirection est suffisante)
        form.submit();
    }

</script>
</html>

        <?php if($discountPercentage > 0): ?>
            <p>Réduction de <?= $discountPercentage ?>% appliquée grâce au code de réduction.</p>
        <?php endif; ?>
        
        <input type="submit" value="Confirmer la réservation" class="reserve-btn">
    </form>
</div>
</body>
</html>
