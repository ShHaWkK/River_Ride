<?php
// Commencer la session
session_start();

// Vérifier si l'administrateur est connecté
if(!isset($_SESSION['admin'])){
    header('Location: Admin_login.php'); // Rediriger vers la page de connexion si l'administrateur n'est pas connecté
    exit();
}
include('Connection.php');
// Traitement pour ajouter un nouveau logement
if (isset($_POST['ajouter_logement'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $capacite = $_POST['capacite'];
    $prix = $_POST['prix'];
    $point_arret_id = $_POST['point_arret_id'];

    // Insérer les données dans la table "hebergements"
    $stmt = $conn->prepare('INSERT INTO hebergements (point_arret_id, nom, description, capacite, prix) VALUES (:point_arret_id, :nom, :description, :capacite, :prix)');
    $stmt->execute(array(
        'point_arret_id' => $point_arret_id,
        'nom' => $nom,
        'description' => $description,
        'capacite' => $capacite,
        'prix' => $prix
    ));
}

// Traitement pour supprimer un logement
if (isset($_POST['supprimer_logement'])) {
    $logement_id = $_POST['logement_id'];

    // Supprimer le logement de la table "hebergements"
    $stmt = $conn->prepare('DELETE FROM hebergements WHERE id = :logement_id');
    $stmt->execute(array('logement_id' => $logement_id));
}

// Récupérer les points d'arrêt existants
$stmt_points_arret = $conn->prepare('SELECT * FROM points_arret');
$stmt_points_arret->execute();
$points_arret = $stmt_points_arret->fetchAll();

// Récupérer les logements pour chaque point d'arrêt
$stmt_hebergements = $conn->prepare('SELECT * FROM hebergements WHERE point_arret_id = :point_arret_id');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administration - Gestion des Logements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            margin: auto;
        }

        form label, form input, form select, form textarea, form button {
            margin: 10px 0;
        }

        form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        section {
            margin: 30px 0;
        }

        h2 {
            margin-bottom: 10px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
        }

        li p {
            display: inline-block;
            margin-right: 10px;
        }

        li button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        li button:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
    <h2>Gestion des logements</h2>
    <a href="add_logement.php">Ajouter un logement</a>
    <a href="delete_logement.php">Supprimer un logement</a>
    <a href="list_logement.php">Editer un logement </a>
</body>
</html>
</body>
</html>
