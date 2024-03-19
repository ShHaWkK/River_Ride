<?php
// Vérifier si une session est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('Connection.php');

// Variable pour stocker les messages d'erreur
$error_message = '';

// vérification de la soumission du formulaire pour l'étape 2
if (isset($_POST['submit_step2'])) {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Vérifier si le mot de passe et la confirmation du mot de passe correspondent
        if ($password !== $confirm_password) {
            $error_message = 'Les mots de passe ne correspondent pas.';
        } else {
            // Enregistrer les informations dans la session pour la troisième étape
            $_SESSION['email'] = $email;
            $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);

            // Rediriger vers la troisième étape du formulaire
            header('Location: inscription_etape3.php');
            exit();
        }
    } else {
        $error_message = 'Veuillez remplir tous les champs.';
    }
}
// if(strlen($_POST['password']) < 6 || strlen($_POST['password']) > 15){
// 	$msg = 'Le mot de passe doit faire entre 6 et 15 caractères.';
// 	header('location: inscription_etape2.php?type=danger&message=' . $msg);
// 	exit;
// }
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Étape 2</title>
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

.input-field input {
    border: none;
    border-radius: 0;
    border-bottom: 1px solid #138d75;
    background-color: transparent;
    height: 40px;
    font-size: 16px;
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
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

    </style>
</head>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('Connection.php');

$error_message = '';

if (isset($_POST['submit_step2'])) {
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $error_message = 'Les Mots de passe ne correspondent pas.';
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);

            header('Location: inscription_etape3.php');
            exit();
        }
    } else {
        $error_message = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE HTML>
<body>
    <div class="registration-form">
        <h2>Étape 2 : Informations supplémentaires</h2>
        <?php if (isset($error_message) && !empty($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Adresse e-mail">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Mot de passe">
            </div>
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Confirmation du mot de passe">
                
            </div>
            <div class="input-field">
    <button type="submit" name="submit_step2">Continuer </button>
            </div>
        </form>
    </div>
</body>
</html>
