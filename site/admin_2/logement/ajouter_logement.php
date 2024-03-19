<?php

$servername = "localhost";
$dbname = "river_ride"; // Nom de votre base de données
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définit le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST["nom"];
    $point_arret_id = $_POST["point_arret_id"];

    // Créer la requête SQL pour ajouter le logement
    $sql = "INSERT INTO logements (nom, point_arret_id) VALUES (:nom, :point_arret_id)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':point_arret_id', $point_arret_id);
        
        $stmt->execute();

        echo "Logement ajouté avec succès!";
    } catch(PDOException $e) {
        echo "Erreur lors de l'ajout du logement : " . $e->getMessage();
    }
}

// Fermer la connexion
$conn = null;

?>

<!-- Vous pouvez ajouter un lien de retour ou d'autres éléments utiles ici. -->
<a href="Admin_Panel.php">Retour à la page principale</a>
