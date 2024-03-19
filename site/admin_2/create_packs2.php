<?php
// Commencer la session
session_start();

// Vérifier si l'administrateur est connecté
if(!isset($_SESSION['admin'])){
    header('Location: Admin_login.php'); // Rediriger vers la page de connexion si l'administrateur n'est pas connecté
    exit();
}
include('Connection.php');

// Récupérer les points d'arrêt depuis la base de données
$stmt = $conn->prepare('SELECT * FROM points_arret');
$stmt->execute();
$pointsArret = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les hébergements depuis la base de données
$stmt = $conn->prepare('SELECT * FROM hebergements');
$stmt->execute();
$hebergements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire de proposition de pack
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $points_arrêt = $_POST['points_arret'];

    // Insérez ici le code pour enregistrer les données du pack proposé dans la base de données

    // Rediriger vers une page de confirmation ou une autre page appropriée
    header('Location: confirmation.php');
    exit();
}
?><!DOCTYPE html>
<html>
<head>
    <title>Proposer un pack</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Styles pour la liste déroulante personnalisée */
        .custom-select {
            position: relative;
            display: inline-block;
            user-select: none;
        }

        .custom-select select {
            display: none;
        }

        .custom-select::before {
            content: "\f078"; /* Icône flèche vers le bas (Font Awesome) */
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #555;
            padding-right: 5px;
            position: absolute;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
        }

        .custom-select.open::before {
            content: "\f077"; /* Icône flèche vers le haut (Font Awesome) */
        }

        .custom-options {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1;
            min-width: 150px;
        }

        .custom-select.open .custom-options {
            display: block;
        }

        .custom-option {
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.1s;
        }

        .custom-option:hover {
            background-color: #ddd;
        }

        /* Autres styles pour le formulaire, les boutons, etc. */
        /* ... */
    </style>
</head>
<body>
    <h1>Proposer un pack</h1>
    <form action="traitement_pack.php" method="post">
        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" required><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" required><br>

        <label for="points_depart">Point de départ :</label>
        <select name="points_depart" id="points_depart" class="custom-select">
            <?php
            // Récupérer les points d'arrêt depuis la base de données
            $stmt = $conn->prepare('SELECT * FROM points_arret');
            $stmt->execute();
            $pointsArret = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Afficher les options pour les points d'arrêt
            foreach ($pointsArret as $point) {
                echo '<option value="' . $point['id'] . '">' . $point['nom'] . '</option>';
            }
            ?>
        </select><br>

        <label for="points_arrivee">Point d'arrivée :</label>
        <select name="points_arrivee" id="points_arrivee" class="custom-select">
            <?php
            // Récupérer les points d'arrêt depuis la base de données (même requête utilisée ici, vous pouvez également créer une fonction pour réutiliser le code)
            $stmt->execute();
            $pointsArret = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Afficher les options pour les points d'arrêt
            foreach ($pointsArret as $point) {
                echo '<option value="' . $point['id'] . '">' . $point['nom'] . '</option>';
            }
            ?>
        </select><br>

        <label for="hebergements">Hébergements :</label><br>
        <?php
        // Récupérer les hébergements depuis la base de données
        $stmt = $conn->prepare('SELECT * FROM hebergements');
        $stmt->execute();
        $hebergements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les options pour les hébergements
        foreach ($hebergements as $hebergement) {
            echo '<input type="checkbox" name="hebergements[]" value="' . $logement['id'] . '">' . $hebergement['nom'] . '<br>';
        }
        ?>
        <button type="submit">Proposer le pack</button>
    </form>
</body>
</html>
