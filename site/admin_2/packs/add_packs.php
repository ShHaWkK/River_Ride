<?php
$servername = "localhost";
$dbname = "river_ride"; 
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si le formulaire est soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Insérer le pack
        $stmt = $conn->prepare("INSERT INTO packs (nom, prix, description, date_debut, date_fin, prix_adulte, prix_enfant) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['prix'], $_POST['description'], $_POST['date_debut'], $_POST['date_fin'], $_POST['prix_adulte'], $_POST['prix_enfant']]);
        
        $pack_id = $conn->lastInsertId();

        // Associer les itinéraires au pack
        foreach ($_POST['itineraire_id'] as $itineraire_id) {
            $stmt = $conn->prepare("UPDATE itineraire SET pack_id = ? WHERE id = ?");
            $stmt->execute([$pack_id, $itineraire_id]);
        }

        echo "Pack ajouté avec succès!";
    }

    // Récupérer tous les itinéraires pour les afficher dans le formulaire
    $stmt = $conn->prepare("SELECT * FROM itineraire");
    $stmt->execute();
    $itineraries = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Pack</title>
    <style>
         
    </style>
</head>
<body>
    <form action="add_packs.php" method="post">
        <label>Nom du pack:</label>
        <input type="text" name="nom" required><br>

        <label>Prix:</label>
        <input type="number" name="prix" step="0.01" required><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br>

        <label>Date de début:</label>
        <input type="date" name="date_debut" required><br>

        <label>Date de fin:</label>
        <input type="date" name="date_fin" required><br>

        <label>Prix adulte:</label>
        <input type="number" name="prix_adulte" step="0.01"><br>

        <label>Prix enfant:</label>
        <input type="number" name="prix_enfant" step="0.01"><br>

        <label>Itinéraires:</label><br>
        <?php foreach ($itineraries as $itinerary): ?>
        <input type="checkbox" name="itineraire_id[]" value="<?php echo $itinerary['id']; ?>">
        <?php echo $itinerary['nom']; ?><br>
        <?php endforeach; ?>

        <input type="submit" value="Ajouter Pack">
    </form>
</body>
</html>
