<?php
include 'Connection.php';

try {
    $stmt = $conn->prepare("SELECT id, nom FROM points_arret");
    $stmt->execute();

    $points_arret = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($points_arret as $point) {
        echo '<input type="checkbox" id="point_' . $point['id'] . '" name="points_arret[]" value="' . $point['id'] . '">';
        echo '<label for="point_' . $point['id'] . '">' . $point['nom'] . '</label><br>';
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
