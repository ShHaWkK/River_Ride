<?php
session_start();

include 'Connection.php';

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Gestion des erreurs PDO

$query = $conn->query("SELECT * FROM points_arret");
$points = $query->fetchAll(PDO::FETCH_ASSOC);

$message = ""; // Pour les messages d'erreur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_points = $_POST['points_arret'];
    $nom_itineraire = $_POST['nom_itineraire'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];


    // Si l'administrateur est connecté
    if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
        $sql = "INSERT INTO itineraire (nom, date_debut, date_fin, notes) VALUES (:nom, :date_debut, :date_fin, :notes)";
        $params = [
            'nom' => $nom_itineraire,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'notes' => $notes
        ];
    }
    // // Si un utilisateur est connecté
    // elseif (isset($_SESSION['user_id'])) {
    //     $sql = "INSERT INTO itineraire (nom, date_debut, date_fin, user_id) VALUES (:nom, :date_debut, :date_fin, :user_id)";
    //     $params = [
    //         'nom' => $nom_itineraire,
    //         'date_debut' => $date_debut,
    //         'date_fin' => $date_fin,
    //         'user_id' => $_SESSION['user_id']
    //     ];
    // } else {
    //     $message = "Erreur : Utilisateur non identifié.";
    // }

    if (empty($message)) {
        try {
            $query = $conn->prepare($sql);
            $query->execute($params);
            $itineraire_id = $conn->lastInsertId();

            foreach ($selected_points as $point_id) {
                $query = $conn->prepare("INSERT INTO itineraire_points_arret (itineraire_id, point_arret_id) VALUES (:itineraire_id, :point_arret_id)");
                $query->execute([
                    'itineraire_id' => $itineraire_id,
                    'point_arret_id' => $point_id
                ]);
            }
            
            echo "<script>document.getElementById('alertBox').style.display = 'block';</script>";
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Créer un itinéraire</title>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        /* Reset de certaines propriétés par défaut pour une meilleure homogénéité entre les navigateurs */
body, h1, h2, h3, p, ul, li, form, input, label {
    margin: 0;
    padding: 0;
    font-family: 'Arial', sans-serif;
}

body {
    background: #cde8d6;
    color: #333;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
}

h1 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #555;
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="text"], input[type="date"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    color: #555;
    background: #f5f5f5;
}

input[type="checkbox"] {
    margin-right: 5px;
}

input[type="submit"] {
    background: #cde8d6;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    color: #333;
    transition: background 0.3s;
}

input[type="submit"]:hover {
    background: #b5d0bc;
}
    
.alert {
    display: none; 
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
}

.alert-content {
    max-width: 300px;
    margin: 20% auto;
    padding: 20px;
    background: #fff;
    border-radius: 5px;
    text-align: center;
}

.alert-content button {
    padding: 5px 10px;
    border: none;
    background: #cde8d6;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    color: #333;
}

        </style>
</head>
<body>
    <div class="container">
        <h1>Créer un itinéraire</h1>
        <form action="" method="post">
    <label for="nom_itineraire">Nom de l'itinéraire :</label>
    <input type="text" name="nom_itineraire" required>

    <label for="date_debut">Date de début :</label>
    <input type="date" name="date_debut" required>

    <label for="date_fin">Date de fin :</label>
    <input type="date" name="date_fin" required>

    <label for="notes">Notes :</label>
    <input type="text" name="notes" required>


            <div id="map" style="height: 250px; margin-bottom: 20px;"></div>

            <p>Sélectionnez les points d'arrêt :</p>
            <?php foreach ($points as $point) : ?>
            <input type="checkbox" id="point_<?= $point['id'] ?>" name="points_arret[]" value="<?= $point['id'] ?>">
            <label for="point_<?= $point['id'] ?>"><?= $point['nom'] ?></label><br>
            <?php endforeach; ?>
            <input type="submit" value="Composer l'itinéraire">
        </form>
    </div>
  <!-- Boîte d'alerte pour la confirmation -->
  <div class="alert" id="alertBox">
        <div class="alert-content">
            <p>Itinéraire créé avec succès !</p>
            <button onclick="closeAlert()">OK</button>
        </div>
    </div>
    <?php
    // Affichage du message d'erreur, si nécessaire
if (!empty($message)) {
    echo "<div class='error-message'>$message</div>";
} 
?>

    <script>
        function closeAlert() {
            document.getElementById('alertBox').style.display = 'none';
        }

      var points = <?php echo json_encode($points); ?>;

var map = L.map('map').setView([48.8566, 2.3522], 12); // Coordonnées de Paris par défaut
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
var markersGroup = L.layerGroup().addTo(map);

points.forEach(function(point) {
    L.marker([point.latitude, point.longitude]).addTo(markersGroup);
});

map.fitBounds(markersGroup.getBounds());

document.querySelector('form').addEventListener('submit', function(event) {
    // PAS de event.preventDefault(); cette fois
    var checkedBoxes = document.querySelectorAll('input[name="points_arret[]"]:checked');
    var selectedPoints = [];

    checkedBoxes.forEach(function(box) {
        var pointId = box.value;
        var pointData = points.find(p => p.id == pointId);
        if (pointData) {
            selectedPoints.push([pointData.latitude, pointData.longitude]);
        }
    });

    updateMapWithPoints(selectedPoints);
});

function updateMapWithPoints(pointsArray) {
    markersGroup.clearLayers();

    pointsArray.forEach(function(point) {
        L.marker(point).addTo(markersGroup);
    });

    map.fitBounds(markersGroup.getBounds());
}


    </script>
</body>

</html>