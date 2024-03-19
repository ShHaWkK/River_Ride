<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    // Met à jour le token pour l'utilisateur
    $stmt = $conn->prepare("UPDATE users SET token=:token, token_expiry=DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email=:email");
    $stmt->bindParam(':token', $token);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $mailLink = "http://localhost/Projet%20River%20Ride/site/client/information/reset_password.php?token=" . $token;

    $mail = new PHPMailer(true); 
    try {
        // Paramètres du serveur
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'riverride573@gmail.com';
        $mail->Password = 'usywmwwlkehtdbbw';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinataires
        $mail->setFrom('riverride573@gmail.com', 'River Ride');
        $mail->addAddress($email);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe';
        $mail->Body    = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe: <a href='" . $mailLink . "'>" . $mailLink . "</a>";

        $mail->send();
       
    } catch (Exception $e) {
        if (mailFunctionSuccess()) { 
            $success_message = "E-mail envoyé avec succès!";
        } else {
            $error_message = "Erreur lors de l'envoi de l'e-mail.";
        }
        
    }
}

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lien de réinitialisation par Email</title>
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
            margin-bottom: 20px;
            color: #138d75;
        }

        .input-field {
            margin-bottom: 20px;
        }

        .input-field label {
            display: block;
            margin-bottom: 10px;
        }

        .input-field input[type="email"] {
            width: 90%;
            padding: 10px;
            border: 2px solid #138d75;
            border-radius: 5px;
            transition: border-color 0.3s ease-in-out;
        }

        .input-field input[type="email"]:focus {
            border-color: #117e64;
        }

        .input-field input[type="submit"] {
            background-color: #138d75;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        .input-field input[type="submit"]:hover {
            background-color: #117e64;
        }

        /* Styles pour les messages */
        .message {
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            font-size: 15px;
            text-align: center;
        }

        .error-message {
            background-color: #fee;
            border: 1px solid #f5c6cb;
            color: #a00;
        }

        .success-message {
            background-color: #edf7ed;
            border: 1px solid #c3e6cb;
            color: #007700;
        }

        .info-message {
            background-color: #e7f5fe;
            border: 1px solid #b5d4e9;
            color: #005580;
        }
    </style>
</head>
<body>
<div class="registration-form">
        <h2>Lien de réinitialisation par Email</h2>

        <form method="post">

            <?php if (isset($error_message) && !empty($error_message)): ?>
                <div class="message error-message"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if (isset($success_message) && !empty($success_message)): ?>
                <div class="message success-message"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <?php if (isset($info_message) && !empty($info_message)): ?>
                <div class="message info-message"><?php echo $info_message; ?></div>
            <?php endif; ?>

            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="input-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-field">
                <input type="submit" value="Réinitialiser le mot de passe">
            </div>
        </form>
    </div>
</body>
</html>