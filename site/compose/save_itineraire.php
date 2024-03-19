<?php
session_start();
include('Connection.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Vérifiez si des points d'arrêt ont été sélectionnés
if (isset($_POST['arrets'])) {
    $arretsSelectionnes = $_POST['arrets'];
   
    $itineraire = implode(",", $arretsSelectionnes);
    
    $userId = $_SESSION['user_id']; // exemple, cela dépend de la façon dont vous gérez les sessions utilisateurs

    $query = "INSERT INTO itineraires (user_id, points_arret, date_creation) VALUES (:userId, :itineraire, NOW())";
    $stmt = $conn->prepare($query);
$stmt->bindParam(":userId", $userId);
$stmt->bindParam(":itineraire", $itineraire);
$stmt->execute();

// Une fois l'itinéraire sauvegardé, redirigez l'utilisateur vers logement.php
header("Location: logement.php");
exit;
}
?>


