<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('Connection.php');

$token = bin2hex(random_bytes(50));
$stmt = $conn->prepare('UPDATE users SET token = ? WHERE id = ?');
$stmt->execute([$token, $_SESSION['id_user']]);

// $confirmation_link = "http://localhost/Projet%20River%20Ride/site/inscription/confirmation_email.php?token=$token";
// $message = "Cliquez sur ce lien pour confirmer votre adresse e-mail: $confirmation_link";

// $headers = "From: riverride@gmail.com\r\n";
// $headers .= "Reply-To: riverride@gmail.com\r\n";
// $headers .= "MIME-Version: 1.0\r\n";
// $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

// mail($_SESSION['email'], 'Confirmation de l\'adresse e-mail', $message, $headers);
// ?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Succès</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #48bf84; /* Vert pour thème nature, kayak, fleuve */
        }

        .success-message {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            text-align: center;
        }

        .success-message i {
            font-size: 48px;
            color: #48bf84; /* Vert pour thème nature, kayak, fleuve */
        }

        .success-message h2 {
            color: #48bf84; /* Vert pour thème nature, kayak, fleuve */
        }

        .success-message p {
            margin-top: 20px;
        }
    </style>
    <meta http-equiv="refresh" content="5;url=inscription_etape4.php">

</head>
<body>
    <div class="success-message">
        <i class="fas fa-check-circle"></i>
        <h2>Inscription réussie !</h2>
        <p>Merci <?php echo $_SESSION['prenom']; ?>, vous êtes maintenant inscrit avec l'adresse e-mail : <?php echo $_SESSION['email']; ?>.</p>
        <!-- Vous pouvez ajouter d'autres informations ici -->
    </div>
</body>
</html>
<?php


