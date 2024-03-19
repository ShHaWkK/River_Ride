
<?php

$servername = "localhost";
$dbname = "river_ride"; // Mettez ici le nom de votre base de données
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Définit le mode d'erreur PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connexion échouée : " . $e->getMessage();
}

// Lorsque le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];
    $pourcentage_reduction = $_POST["pourcentage_reduction"];

    $stmt = $conn->prepare("INSERT INTO offres_promotionnelles (nom, date_debut, date_fin, pourcentage_reduction) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $date_debut, $date_fin, $pourcentage_reduction]);

    echo "Offre ajoutée avec succès!";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ajouter Offre</title>
        <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin-top: 50px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 8px 15px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    a, button {
        background-color: #007BFF;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        margin: 4px 2px;
        cursor: pointer;
        border: none;
        border-radius: 4px;
    }

    button[type=submit] {
        background-color: #4CAF50;
    }

    a:hover, button:hover {
        opacity: 0.8;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: #555;
    }

    input[type=text], input[type=date], input[type=number], select {
        width: 100%;
        padding: 8px 15px;
        margin: 8px 0;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
    }

    input[type=submit] {
        width: 100%;
    }
</style>

<form action="admin_add_offer.php" method="post">
    <label for="nom">Nom du Code de Réduction:</label>
    <input type="text" name="nom" required>
    
    <label for="date_debut">Date de début:</label>
    <input type="date" name="date_debut" required>
    
    <label for="date_fin">Date de fin:</label>
    <input type="date" name="date_fin" required>
    
    <label for="pourcentage_reduction">Réduction (%):</label>
    <input type="number" name="pourcentage_reduction" step="0.01" required>
    
    <button type="submit">Ajouter l'offre</button>
</form>