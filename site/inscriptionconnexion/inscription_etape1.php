<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('Connection.php');

$error_message = '';

if (isset($_POST['submit_step1'])) {
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['date_naissance'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];

        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['date_naissance'] = $date_naissance;

        header('Location: inscription_etape2.php');
        exit();
    } else {
        $error_message = 'Veuillez remplir tous les champs.';
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - Étape 1</title>
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

    </style>
</head>
<body>
    <div class="registration-form">
        <h2>Étape 1 : Informations de base</h2>
        <?php if (isset($error_message) && !empty($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" name="nom" placeholder="Nom">
            </div>
            <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" name="prenom" placeholder="Prénom">
          
            </div>
            <div class="input-field">
                <i class="fas fa-calendar"></i>
                <input type="date" name="date_naissance" placeholder="Date de naissance">
               
            </div>
            <div class="input-field">
    <button type="submit" name="submit_step1">Continuer </button>
            </div>

        </form>
    </div>
</body>
</html>
