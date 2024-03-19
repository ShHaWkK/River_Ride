<?php


try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'], $_POST['token'])) {
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = $_POST['token'];

    // Réinitialisez le mot de passe
    $stmt = $conn->prepare("UPDATE users SET mot_de_passe=:password, token=NULL, token_expiry=NULL WHERE token=:token AND token_expiry > NOW()");
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    if ($affectedRows) {
        echo "Mot de passe réinitialisé avec succès.";
        header('Location: ../../inscriptionconnexion/connexion.php'); // Redirige vers connexion.php
        exit; // Assurez-vous de sortir pour arrêter le script après avoir envoyé l'en-tête de redirection.
    } else {
        $error_message = "Erreur lors de la réinitialisation du mot de passe ou le token est invalide/expiré.";
;
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Réinitialisation du mot de passe</title>
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
            position: relative;
            /* left: 15px; */
            top: calc(50% - 10px);
        }

        .input-field input {
            border: none;
            border-radius: 0;
            border-bottom: 1px solid #138d75;
            background-color: transparent;
            height: 40px;
            font-size: 16px;
            padding-left: 40px;  /* to make space for the icon */
        }

        .input-field input::placeholder {
            color: #999;
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

        .extra {
            text-align: right;
            margin-top: 10px;
        }

        .extra a {
            color: #138d75;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .extra a:hover {
            color: #117e64;
        }
        
        .error-message {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px 20px;
            margin: 20px 0;
            border-radius: 5px;
            color: #721c24;
            font-weight: bold;
            border: 1px solid transparent;
        }

    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Réinitialisation du mot de passe</h2>
        <form method="post">
        <?php if (isset($error_message) && !empty($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Nouveau mot de passe" required>
            </div>
            <div class="input-field">
                <button type="submit">Valider</button>
            </div>
        </form>
    </div>
</body>
</html>
