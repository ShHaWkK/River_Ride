<?php
session_start();

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['nom']) && isset($_POST['prix']) && isset($_POST['date_debut']) && isset($_POST['date_fin']) && isset($_POST['itineraire_id']) && isset($_POST['logement_id'])) {
        
        $id = $_POST['id'];
        $nom = $_POST['nom'];
        $prix = $_POST['prix'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $itineraire_id = $_POST['itineraire_id'];
        $logement_id = $_POST['logement_id'];

        try {
            $stmt = $conn->prepare("UPDATE packs SET nom = :nom, prix = :prix, date_debut = :date_debut, date_fin = :date_fin, itineraire_id = :itineraire_id, logement_id = :logement_id WHERE id = :id");
            
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':date_debut', $date_debut);
            $stmt->bindParam(':date_fin', $date_fin);
            $stmt->bindParam(':itineraire_id', $itineraire_id);
            $stmt->bindParam(':logement_id', $logement_id);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
            
            header("Location: list_pack.php");
            exit();
        } catch(PDOException $e) {
            echo "Erreur lors de la mise à jour: " . $e->getMessage();
        }
    } else {
        echo "Erreur: Tous les champs doivent être remplis!";
    }
}
?>
