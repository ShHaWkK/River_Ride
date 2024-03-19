<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logement_id'])) {
    $logement_id = $_POST['logement_id'];

    $sql = "DELETE FROM logements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$logement_id]);
}

$sql_logements = "SELECT id, nom FROM logements";
$logements = $conn->query($sql_logements)->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="delete_logement.php" method="post">
    Choisir le logement à supprimer:
    <select name="logement_id">
        <?php foreach ($logements as $logement) { ?>
            <option value="<?= $logement['id'] ?>"><?= $logement['nom'] ?></option>
        <?php } ?>
    </select>
    <input type="submit" value="Supprimer">
</form>
