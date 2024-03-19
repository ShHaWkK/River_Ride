<?php
include('Connection.php');

$nom = 'Ragnar';
$email = 'Ragnar@gmail.com';
$mot_de_passe = '123456';

// Hasher le mot de passe
$mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

// Préparation de la requête
$stmt = $conn->prepare("INSERT INTO administrateurs (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe)");

// Execution de la requête
$stmt->execute([
    'nom' => $nom,
    'email' => $email,
    'mot_de_passe' => $mot_de_passe_hash,
]);

echo "Nouvel admin ajouté avec succès !";
?>
