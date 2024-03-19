<?php
session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('Connection.php');

$error_message = '';

if (isset($_POST['submit_step3'])) {
    if (isset($_POST['experience']) && isset($_POST['logement'])) {
        $experience = $_POST['experience'];
        $logement = implode(', ', $_POST['logement']);
        

      
            // Enregistrez les informations dans la base de données
            $stmt = $conn->prepare('INSERT INTO users (nom, prenom, email, mot_de_passe, niveau_experience, date_naissance, preference_logement) VALUES (:nom, :prenom, :email, :mot_de_passe, :niveau_experience, :date_naissance, :preference_logement)');
            $stmt->execute(array(
                'nom' => $_SESSION['nom'],
                'prenom' => $_SESSION['prenom'],
                'email' => $_SESSION['email'],
                'mot_de_passe' => $_SESSION['password'],
                'niveau_experience' => $experience,
                'date_naissance' => $_SESSION['date_naissance'],
                'preference_logement' => $logement
            ));

            // Récupérez l'ID inséré
            $user_id = $conn->lastInsertId();

            // Ajoutez l'ID à la session
            $_SESSION['id_user'] = $user_id;

header('Location: inscription_succes.php');
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
    <title>Inscription - Étape 3</title>
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

.input-field select {
    border: none;
    border-radius: 0;
    border-bottom: 1px solid #138d75;
    background-color: transparent;
    height: 60px;
    font-size: 16px;
    width: 100%;
}

.input-field select option {
    color: #138d75;
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
        <h2>Étape 3 : Confirmation</h2>
        <?php if (isset($error_message) && !empty($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-field">
                <label for="experience">Niveau d'expérience :</label>
                <select name="experience" id="experience">
                    <option value="Debutant">Débutant</option>
                    <option value="Intermediaire">Intermédiaire</option>
                    <option value="Professionnel">Professionnel</option>
                </select>
            </div>
            <div class="input-field">
                <label for="logement">Préférence de logement :</label>
                <select name="logement[]" id="logement" multiple>
                    <option value="Camping">Camping</option>
                    <option value="Hotel">Hôtel</option>
                    <option value="Auberge">Auberge</option>
                </select>
            </div>
            <div class="input-field">
    <button type="submit" name="submit_step3">Validation</button>
            </div>
        </form>
        
    </div>
</body>
</html>
