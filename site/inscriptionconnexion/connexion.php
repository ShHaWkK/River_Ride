<?php
include 'Connection.php';

session_start();

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification de la connexion dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        // Connexion réussie, stocker les informations dans la session
        $_SESSION['email'] = $email;
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../index.php");
        exit;
    } else {
        $error_message = "Email ou mot de passe incorrect";
    }
    
}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
<style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f3f3f3;
        }

        .login-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
            color: #138d75;
        }

        .input-field {
            margin-bottom: 20px;
            padding: 10px;
        }

        .input-field i {
            font-size: 20px;
            color: #138d75;
            margin-right: 10px;
        }

        .input-field input[type="email"],
        .input-field input[type="password"] {
            border: none;
            border-bottom: 1px solid #138d75;
            background-color: transparent;
            height: 30px;
            font-size: 16px;
            width: 100%;
        }

        .input-field input[type="email"]:focus,
        .input-field input[type="password"]:focus {
            outline: none;
        }

        .input-field button {
            background-color: #138d75;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        .input-field button:hover {
            background-color: #117e64;
        }

        .extra {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .extra a {
            color: #138d75;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
            padding: 20px;
        }

        .extra a:hover {
            color: #117e64;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Connexion</h2>
        <?php if (isset($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Mot de passe" required>
            </div>
            <div class="input-field">
                <button type="submit" name="login">Se connecter</button>
            </div>
        </form>
        <div class="extra">
            <a href="../client/information/forgot_password.php">Mot de passe oublié ?</a>
            <a href="inscription_etape1.php"> Inscrivez-vous</a>
        </div>
    </div>
</body>
</html>


