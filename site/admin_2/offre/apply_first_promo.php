<?php

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Supposons que l'ID utilisateur soit obtenu après une connexion ou une autre méthode sécurisée
$user_id = $_SESSION['user_id'];  // Par exemple, si vous utilisez les sessions pour gérer les connexions

$stmt = $conn->prepare("SELECT first_promo_used FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row['first_promo_used'] == 0) {
    // L'utilisateur est éligible pour la promotion
    
    // Appliquez la réduction et effectuez la réservation
    // Vous pouvez intégrer votre logique de réservation ici

    // Mettez à jour la colonne first_promo_used pour cet utilisateur
    $updateStmt = $conn->prepare("UPDATE users SET first_promo_used = 1 WHERE id = :user_id");
    $updateStmt->bindParam(':user_id', $user_id);
    $updateStmt->execute();

    echo "Promotion appliquée avec succès pour votre première réservation!";
} else {
    // L'utilisateur a déjà utilisé la promotion pour la première réservation
    echo "Vous avez déjà utilisé la promotion pour la première réservation.";
    // Procédez normalement sans appliquer de réduction
    // Vous pouvez intégrer votre logique de réservation standard ici
}

?>
