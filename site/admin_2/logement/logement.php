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
?>
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
?>
<?php
// Requête pour récupérer les points d'arrêt depuis la base de données
$sql = "SELECT id, nom FROM points_arret";
$stmt = $conn->query($sql);
$points_arret = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un logement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f7f9;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #0077b6;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #0077b6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #005f8d;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Ajouter un logement</h2>
    <form action="ajouter_logement.php" method="post">
        <label for="nom">Nom du logement :</label>
        <input type="text" id="nom" name="nom" required>

        <!-- Je suppose que vous avez d'autres champs comme description, capacité, prix etc... Ajoutez-les ici. -->

        <label for="point_arret_id">Point d'arrêt :</label>
        <select id="point_arret_id" name="point_arret_id" required>
            <!-- Afficher les options des points d'arrêt depuis la base de données -->
            <?php foreach ($points_arret as $point) { ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php } ?>
        </select>

        <input type="submit" value="Ajouter">
    </form>
</div>

</body>
</html>
