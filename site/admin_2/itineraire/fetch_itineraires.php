<?php
include 'Connection.php';

try {
    $stmt = $conn->prepare("SELECT id, nom FROM itineraire");
    $stmt->execute();

    $itineraire = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itineraire as $it) {
        echo '<option value="' . $it['id'] . '">' . $it['nom'] . '</option>';
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
