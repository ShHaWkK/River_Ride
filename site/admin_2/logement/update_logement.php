<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logement_id'])) {
    $logement_id = $_POST['logement_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $capacite = $_POST['capacite'];
    $prix_normal = $_POST['prix_normal'];
    $prix_promotionnel = $_POST['prix_promotionnel'];

    $sql = "UPDATE logements SET nom = ?, description = ?, capacite = ?, prix_normal = ?, prix_promotionnel = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nom, $description, $capacite, $prix_normal, $prix_promotionnel, $logement_id]);
}

$sql_logements = "SELECT id, nom, description, capacite, prix_normal, prix_promotionnel FROM logements";
$logements = $conn->query($sql_logements)->fetchAll(PDO::FETCH_ASSOC);
?>

<form action="update_logement.php" method="post">
    Choisir le logement à modifier:
    <select name="logement_id" onchange="populateFields(this.value)">
        <?php foreach ($logements as $logement) { ?>
            <option value="<?= $logement['id'] ?>"><?= $logement['nom'] ?></option>
        <?php } ?>
    </select>
    Nom: <input type="text" name="nom" id="nom">
    Description: <input type="text" name="description" id="description">
    Capacité: <input type="number" name="capacite" id="capacite">
    Prix normal: <input type="number" name="prix_normal" id="prix_normal" step="0.01">
    Prix promotionnel: <input type="number" name="prix_promotionnel" id="prix_promotionnel" step="0.01">
    <input type="submit" value="Mettre à jour">
</form>

<script>
function populateFields(logement_id) {
    const logements = <?= json_encode($logements) ?>;
    const selectedLogement = logements.find(l => l.id == logement_id);
    document.getElementById('nom').value = selectedLogement.nom;
    document.getElementById('description').value = selectedLogement.description;
    document.getElementById('capacite').value = selectedLogement.capacite;
    document.getElementById('prix_normal').value = selectedLogement.prix_normal;
    document.getElementById('prix_promotionnel').value = selectedLogement.prix_promotionnel || '';
}
</script>
