


<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'Connection.php';

session_start();

if (!isset($_SESSION['email'])) {
    echo "Session expirée ou e-mail non fourni.";
    exit;
}

$email = $_SESSION['email'];

function generateToken($length = 8) {
    return str_pad(rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

// Seulement générer et envoyer le token si l'utilisateur n'a pas encore soumis le formulaire
if (!isset($_POST['token'])) {
    $token = generateToken();

    // Enregistrement du token dans la base de données avec une date d'expiration
    $expiryDate = new DateTime();
    $expiryDate->modify('+30 minutes');
    $stmt = $conn->prepare("UPDATE users SET token = :token, token_expiry = :expiry WHERE email = :email");
    $stmt->execute(['token' => $token, 'expiry' => $expiryDate->format('Y-m-d H:i:s'), 'email' => $email]);

    $mail = new PHPMailer(true);
    try {
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
        $mail->Subject = 'Code de Validation';
        $mail->Body = "Voici votre code de validation: <b>" . htmlspecialchars($token) . "</b>";
        
        $mail->send();
       
    } catch (Exception $e) {
        echo "L'e-mail n'a pas pu être envoyé. Erreur: " . htmlspecialchars($mail->ErrorInfo);
    }
} else {
    $enteredToken = $_POST['token'];

    // Vérifier le token dans la base de données et son expiration
    $stmt = $conn->prepare("SELECT token, token_expiry FROM users WHERE email = :email AND token = :token");
    $stmt->execute(['email' => $email, 'token' => $enteredToken]);

    $row = $stmt->fetch();
    if ($row) {
        $expiryDate = new DateTime($row['token_expiry']);
        $now = new DateTime();

        if ($now < $expiryDate) {
            $stmt = $conn->prepare("UPDATE users SET confirme = 1, token = NULL, token_expiry = NULL WHERE email = :email");
            $stmt->execute(['email' => $email]);
            header("Location: capcha.php");
            exit;
        } else {
            echo "Votre code de validation a expiré. Veuillez demander un nouveau code.";
        }
    } else {
        // Debug
        echo "Token entré: " . htmlspecialchars($enteredToken) . "<br>";
        echo "Token dans la base: " . htmlspecialchars($row['token'] ?? 'N/A') . "<br>";
        echo "Code de validation invalide.";
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Code de Validation</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #cde8d6;
        }

        .registration-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .registration-form h2 {
            text-align: left;
            margin-bottom: 30px;
            color: #138d75;
        }

        .input-field {
            margin-bottom: 30px;
            border-color: #138d75;
            padding: 15px;
        }

        .input-field i {
            font-size: 20px;
            color: #138d75;
        }

        .input-field input[type="text"] {
            border: none;
            border-radius: 0;
            border-bottom: 1px solid #138d75;
            background-color: transparent;
            height: 40px;
            font-size: 16px;
            width: 100%;
        }

        .input-field button {
            background-color: #138d75;
            border: none;
            border-radius: 5px;
            padding: 15px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        .input-field button:hover {
            background-color: #117e64;
        }
        
        .show-message {
            opacity: 1; /* Rend le message visible */
        }
    </style>
   <!-- Script pour ajouter la classe d'animation après l'envoi du formulaire -->
   <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmationMessage = document.querySelector('.confirmation-message');
            <?php if (isset($_POST['token'])) : ?>
                confirmationMessage.classList.add('show-message');
            <?php endif; ?>
        });
    </script>
</head>
<body>
    <div class="registration-form">
        <h2>Entrez le code de validation</h2>
        
        <form method="POST">
            <div class="input-field">
                <label for="token">Entrez le code de validation :</label>
                <input type="text" name="token" id="token" required>
            </div>
            <div class="input-field">
                <button type="submit" name="Valider">Valider</button>
            </div>
           
    </div>
</body>
</html>