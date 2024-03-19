<?php
session_start();


try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}

$message = "";

if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour procéder au paiement");
}

$reservation_id = null;
if (isset($_GET['reservation_id']) && !empty($_GET['reservation_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
}

header("Location: confirmation.php?reservation_id=$reservation_id");
exit();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Mode de Paiement</title>
    
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

.message {
    background-color: #f9c9c3;
    padding: 10px;
    border-radius: 5px;
    margin: 20px 0;
    color: #a82515;
    text-align: center;
}

#payment-form {
    margin-top: 20px;
}

#card-element {
    margin-top: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    border-radius: 5px;
}

#submit-payment {
    display: block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #2e5d34;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

#submit-payment:hover {
    background-color: #1f4a29;
    color: #fff;
}

#card-errors {
    margin-top: 10px;
    color: #a82515;
    font-size: 14px;
}
.input-group {
    margin-top: 15px;
}

.input-group label {
    display: block;
    margin-bottom: 5px;
}

.input-group i {
    margin-right: 10px;
}

#submit-payment {
    /* ... Autres styles ... */
    display: inline-block;
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #2e5d34;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

#submit-payment i {
    margin-right: 5px;
}
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Lien vers Font Awesome -->
</head>
<body>
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

        // Soumettre le formulaire (vous pouvez supprimer cette ligne si la redirection est suffisante)
        form.submit();
    }

</script>
</html>