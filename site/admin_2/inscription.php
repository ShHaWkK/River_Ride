<?php
// Vérifier si une session est active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('Connection.php');

// Variable pour stocker les messages d'erreur
$error_message = '';

// vérification de la soumission du formulaire pour l'étape 1
if (isset($_POST['submit_step1'])) {
    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['date_naissance'])&& isset($_POST['preference_logement'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $date_naissance = $_POST['date_naissance'];

        // Vérifier si l'utilisateur existe déjà dans la base de données
        $stmt = $conn->prepare('SELECT * FROM utilisateurs WHERE email = :email');
        $stmt->execute(array('email' => $email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $error_message = 'Un compte avec cette adresse e-mail existe déjà.';
        } else {
            // Enregistrer les informations dans la session pour la deuxième étape
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['date_naissance'] = $date_naissance;

            // Rediriger vers la deuxième étape du formulaire
            header('Location: inscription.php?step=2');
            exit();
        }
    } else {
        $error_message = 'Veuillez remplir tous les champs.';
    }
}

// vérification de la soumission du formulaire pour l'étape 2
if (isset($_POST['submit_step2'])) {
    // Traitez les informations de l'étape 2 ici
    // Vous pouvez les récupérer à partir de $_POST ou $_SESSION
    // Puis passez à l'étape suivante ou affichez le formulaire de l'étape 3
}

// vérification de la soumission du formulaire pour l'étape 3
if (isset($_POST['submit_step3'])) {
    // Traitez les informations de l'étape 3 ici
    // Vous pouvez les récupérer à partir de $_POST ou $_SESSION
    // Enregistrez-les dans la base de données ou effectuez d'autres actions
    // Affichez un message de succès ou redirigez vers une autre page
}
?><!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
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

        .registration-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .registration-form h2 {
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

        .input-field input::placeholder {
            color: #999;
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

        /* New styles for the registration form */
        .registration-form {
            padding: 40px;
            max-width: 400px;
            width: 100%;
            border: 1px solid #ddd;
        }

        .registration-form h2 {
            text-align: left;
            margin-bottom: 30px;
            color: #007bff;
        }

        .input-field {
            margin-bottom: 30px;
            border-color: #007bff;
            padding: 15px;
        }

        .input-field i {
            font-size: 20px;
            color: #007bff;
        }

        .input-field input {
            border: none;
            border-radius: 0;
            border-bottom: 1px solid #007bff;
            background-color: transparent;
            height: 40px;
            font-size: 16px;
        }

        .input-field input::placeholder {
            color: #999;
        }

        .input-field button {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            padding: 15px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        .input-field button:hover {
            background-color: #0056b3;
        }

        .extra {
            text-align: right;
            margin-top: 10px;
        }

        .extra a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease-in-out;
        }

        .extra a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="registration-form">
        <?php
        if (isset($_GET['step']) && $_GET['step'] === '2') : ?>
            <h2>Étape 2 : Informations supplémentaires</h2>
            <!-- Formulaire de l'étape 2 -->
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
                <button type="submit" name="submit_step2">Continuer vers l'étape 3</button>
            </form>
        <?php elseif (isset($_GET['step']) && $_GET['step'] === '3') : ?>
            <h2>Étape 3 : Confirmation</h2>
            <!-- Afficher les informations saisies dans les étapes précédentes -->
            <p>Nom : <?php echo $_SESSION['nom']; ?></p>
            <p>Prénom : <?php echo $_SESSION['prenom']; ?></p>
            <p>Date de naissance : <?php echo $_SESSION['date_naissance']; ?></p>
            <p>Email : <?php echo $_SESSION['email']; ?></p>
            <p>Mot de passe : **********</p>
            <!-- ... Afficher d'autres informations de l'étape 2 ... -->

            <!-- Formulaire de confirmation et d'enregistrement -->
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
                <button type="submit" name="submit_step3">Confirmer et s'inscrire</button>
            </form>
        <?php else : ?>
            <h2>Étape 1 : Informations de base</h2>
            <?php if (isset($error_message) && !empty($error_message)) : ?>
                <div class="error-message">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>
            <!-- Formulaire de l'étape 1 -->
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

                <button type="submit" name="submit_step1">Continuer vers l'étape 2</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
