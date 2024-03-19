<?php
// Vérifier si une session est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('Connection.php');

// Variable pour stocker les messages d'erreur
$error_message = '';

// vérification de la soumission du formulaire
if (isset($_POST['submit'])) {
    if (isset($_POST['adminEmail']) && isset($_POST['password'])) {
        $adminEmail = $_POST['adminEmail'];
        $password = $_POST['password'];

        // préparation de la requête
        $stmt = $conn->prepare('SELECT * FROM administrateurs WHERE email = :email');
        $stmt->execute(array('email' => $adminEmail));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            // vérification du mot de passe
            if (password_verify($password, $row['mot_de_passe'])) {
                $_SESSION['admin'] = $row['nom']; // créer une session pour l'administrateur
                header('Location: Admin_Panel.php'); // rediriger vers le panel d'administration
                exit();
            } else {
                $error_message = 'Mot de passe incorrect';
            }
        } else {
            $error_message = 'Adresse e-mail non trouvée';
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <link rel="stylesheet" href="style.css"> <!-- Assurez-vous que le fichier style.css est correctement lié -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }
        .login-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }
        .login-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .input-field {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }
        .input-field i {
            margin-right: 10px;
        }
        .input-field input {
            flex: 1;
            border: none;
            outline: none;
        }
        
        .input-field button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .extra {
            text-align: center;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Connexion Admin</h2>
        <?php if (isset($error_message) && !empty($error_message)): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="adminEmail" placeholder="Adresse e-mail">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Mot de passe">
            </div>

            <button type="submit" name="submit">Me connecter</button>
            
        </form>
    </div>
</body>
</html>
