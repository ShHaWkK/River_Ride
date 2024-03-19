// get_promotion.php
<?php
include 'connection.php';
session_start();

$date = $_POST['date'];

$sql = "SELECT * FROM logements WHERE :date BETWEEN debut_promotion AND fin_promotion";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':date', $date);
$stmt->execute();

$logements_promotionnels = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichez $logements_promotionnels à l'utilisateur
