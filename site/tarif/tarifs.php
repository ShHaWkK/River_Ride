<?php
session_start();

$title = "Tarif";
include("../includes/header2.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tarifs</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<h1>Tarifs</h1>

<table>
    <thead>
        <tr>
            <th>Parcours</th>
            <th>Distance et temps</th>
            <th>Tarif - Adulte</th>
            <th>Tarif -12ans</th>
        </tr>
    </thead>
    <tbody>
        <!-- Matinée category -->
        <tr>
            <td rowspan="1">Matinée<br>La Classique : Saint Dyé-Base Loire Kayak</td>
            <td>11km – 1h45</td>
            <td>25 €/pers</td>
            <td>18 €/pers</td>
        </tr>

        <!-- Après midi category -->
        <tr>
            <td rowspan="2">Après midi<br>La Mini : Montlivault-Base Loire Kayak<br>La Classique : Saint Dyé-Base Loire Kayak</td>
            <td>6,5km – 1h00</td>
            <td>20 €/pers</td>
            <td>13 €/pers</td>
        </tr>
        <tr>
            <td>11km – 1h45</td>
            <td>25 €/pers</td>
            <td>18 €/pers</td>
        </tr>

        <!-- Journée category -->
        <tr>
            <td rowspan="3">Journée<br>La Découverte : Cavereau-Base Loire Kayak<br>L’aventure : Base Loire Kayak-Chaumont<br>La Sportive : Saint Dyé-Chaumont</td>
            <td>20km – 3h30</td>
            <td>30 €/pers</td>
            <td>23 €/pers</td>
        </tr>
        <tr>
            <td>20km – 3h30</td>
            <td>Disponible en 2024</td>
            <td>Disponible en 2024</td>
        </tr>
        <tr>
            <td>31km – 5h15</td>
            <td>Disponible en 2024</td>
            <td>Disponible en 2024</td>
        </tr>
    </tbody>
</table>

<!-- Add a link to your external CSS here if you have one -->
<link rel="stylesheet" href="path_to_your_stylesheet.css">

