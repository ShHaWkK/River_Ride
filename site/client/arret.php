<?php
session_start();
include 'connection.php';
?>



<form action="create_itinerary.php" method="post">
    <h3>Sélectionnez les points d'arrêt pour votre itinéraire:</h3>

    <?php
    $query = "SELECT * FROM points_arret";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<input type="checkbox" name="points[]" value="' . $row["id"] . '"> ' . $row["nom"] . '<br>';
    }
    ?>    

    <input type="text" name="itineraire_nom" placeholder="Nom de l'itinéraire" required>
    <input type="date" name="date_debut" placeholder="Date de début" required>
    <input type="date" name="date_fin" placeholder="Date de fin" required>
    <input type="submit" value="Créer itinéraire">
</form>
