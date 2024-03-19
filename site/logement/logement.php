<?php
session_start();

include 'Connection.php';

$logements = [];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['action']) && $_GET['action'] === 'search' && isset($_GET['term'])) {
        $term = $_GET['term'];
        $stmt = $conn->prepare("SELECT * FROM logements WHERE nom LIKE ?");
        $stmt->execute(["%$term%"]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM logements");
        $stmt->execute();
    }

    $logements = $stmt->fetchAll();

} catch(PDOException $e) {
    die("Erreur: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de logements</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #e8f5e9;
            margin: 0;
            padding: 0;
        }

        .search-bar {
            display: flex;
            justify-content: center;
            padding: 30px;
            background-color: #4caf50;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .search-bar input, .search-bar button {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 50px;
            transition: all 0.3s;
        }

        .search-bar input {
            flex-grow: 1;
            outline: none;
            background: white;
            font-size: 18px;
        }

        .search-bar button {
            background: #ffffff;
            color: #4caf50;
            cursor: pointer;
            font-weight: bold;
        }

        .search-bar button:hover {
            background: #e0e0e0;
        }

        .logement {
            border: 1px solid #a5d6a7;
            padding: 20px;
            margin: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logement:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        .logement h2 {
            margin-top: 0;
            color: #2e7d32;
        }

        button {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            transition: background-color 0.3s, transform 0.3s;
        }

        button:hover {
            background-color: #388e3c;
            transform: scale(1.05);
        }

    </style>
</head>
<?php
require '../includes/header2.php';
?>
<body>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Recherche de logement...">
        <button onclick="searchLogements()">Rechercher</button>
    </div>
    <div id="results">
        <?php foreach ($logements as $logement): ?>
            <div class="logement">
                <h2><?php echo htmlspecialchars($logement['nom'], ENT_QUOTES, 'UTF-8'); ?></h2>
                <p>Prix: <?php echo htmlspecialchars($logement['prix_normal'], ENT_QUOTES, 'UTF-8'); ?>€</p>
                <p>Capacité: <?php echo htmlspecialchars($logement['capacite'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Date de début: <?php echo htmlspecialchars($logement['ferme_de'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Date de fin: <?php echo htmlspecialchars($logement['ferme_a'], ENT_QUOTES, 'UTF-8'); ?></p>
                <button onclick="reserve(<?php echo intval($logement['id']); ?>)">Réserver</button>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function searchLogements() {
            const searchTerm = document.getElementById('searchInput').value;
            window.location.href = `logement.php?action=search&term=${searchTerm}`;
        }

        function reserve(id) {
            console.log(`Logement réservé avec l'ID: ${id}`);
            // Ajoutez des logiques pour gérer la réservation ici
        }
    </script>
</body>
</html>
