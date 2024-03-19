<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Connection.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Offres</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-top: 50px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    nav {
        margin-top: 20px;
    }

    nav a {
        background-color: #007BFF;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin: 4px 2px;
        cursor: pointer;
        border: none;
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Offres</h1>
        <nav>
            <a href="list_offer.php">Liste des Offres</a>
            <a href="admin_add_offer.php">Ajouter une Offre</a>
           
        </nav>
    </div>
</body>
</html>
