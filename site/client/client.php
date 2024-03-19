<!DOCTYPE html>
<html>
<head>
    <title>Composer son itinéraire kayak</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        h1, h2 {
            color: #007BFF;
        }

        #map {
            height: 400px;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select,
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Composer son itinéraire kayak</h1>

    <!-- Formulaire pour ajouter un point d'arrêt -->
    <h2>Ajouter un point d'arrêt :</h2>
    <form id="formAjouterPointArret" method="post">
        <label for="nom_point">Nom du point d'arrêt :</label>
        <input type="text" name="nom_point" required>
        <label for="description_point">Description :</label>
        <textarea name="description_point" required></textarea>
        <label for="latitude">Latitude :</label>
        <input type="text" name="latitude" required>
        <label for="longitude">Longitude :</label>
        <input type="text" name="longitude" required>
        <input type="submit" name="ajouter_point_arret" value="Ajouter">
    </form>

    <!-- Carte Leaflet -->
    <div id="map"></div>

    <!-- Liste des points d'arrêt disponibles -->
    <h2>Points d'arrêt disponibles :</h2>
    <ul id="listePointsArret">
    <?php
try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}



        // Fonction pour ajouter un point d'arrêt dans la base de données
        function ajouterPointArret($conn, $nom, $description, $latitude, $longitude) {
            $sql = "INSERT INTO points_arret (nom, description, latitude, longitude) VALUES (:nom, :description, :latitude, :longitude)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':latitude', $latitude);
            $stmt->bindParam(':longitude', $longitude);

            try {
                $stmt->execute();
                echo "Le point d'arrêt a été ajouté avec succès !";
            } catch (PDOException $e) {
                echo "Une erreur s'est produite lors de l'ajout du point d'arrêt : " . $e->getMessage();
            }
        }

        // Traiter l'ajout du point d'arrêt s'il y a des données soumises via le formulaire
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['ajouter_point_arret'])) {
            $nom = $_POST['nom_point'];
            $description = $_POST['description_point'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];

            ajouterPointArret($conn, $nom, $description, $latitude, $longitude);
        }

        // Récupérer les points d'arrêt existants depuis la base de données
        $points_arret = array();
        $sql = "SELECT * FROM points_arret";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $points_arret = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($points_arret as $point) {
            echo "<li>{$point['nom']} ({$point['latitude']}, {$point['longitude']})</li>";
        }
        ?>
    </ul>

    <!-- Formulaire pour ajouter un hébergement -->
    <h2>Ajouter un hébergement :</h2>
    <form id="formAjouterHebergement" method="post">
        <label for="point_arret_id">Point d'arrêt :</label>
        <select name="point_arret_id" required>
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="nom_hebergement">Nom de l'hébergement :</label>
        <input type="text" name="nom_hebergement" required>
        <label for="description_hebergement">Description :</label>
        <textarea name="description_hebergement" required></textarea>
        <label for="capacite_hebergement">Capacité :</label>
        <input type="number" name="capacite_hebergement" required>
        <label for="prix_hebergement">Prix :</label>
        <input type="number" name="prix_hebergement" step="0.01" required>
        <input type="submit" name="ajouter_hebergement" value="Ajouter">
    </form>

    <!-- Nouveau formulaire pour le trajet itinéraire kayak -->
    <h2>Créer un trajet itinéraire kayak :</h2>
    <form id="formItineraire">
        <label for="points_depart">Point de départ :</label>
        <select name="points_depart">
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="points_arrivee">Point d'arrivée :</label>
        <select name="points_arrivee">
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Créer l'itinéraire">
    </form>

    <h2>Itinéraire kayak :</h2>
    <ul id="itineraireSurFleuve">
        <!-- Les points d'arrêt sélectionnés seront ajoutés ici via JavaScript -->
    </ul>

    <!-- Nouveau formulaire pour acheter des services complémentaires -->
    <h2>Acheter des services complémentaires :</h2>
    <form id="formAcheterServices">
        <label for="point_arret_id">Point d'arrêt :</label>
        <select name="point_arret_id">
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="nombre_bagages">Nombre de bagages :</label>
        <input type="number" name="nombre_bagages" required>
        <input type="submit" value="Acheter">
    </form>

    <!-- Nouveau formulaire pour réserver un hébergement -->
    <h2>Réserver un hébergement :</h2>
    <form id="formReserverHebergement">
        <label for="point_arret_id">Point d'arrêt :</label>
        <select name="point_arret_id">
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="nombre_personnes">Nombre de personnes :</label>
        <input type="number" name="nombre_personnes" required>
        <label for="date_arrivee">Date d'arrivée :</label>
        <input type="date" name="date_arrivee" required>
        <input type="submit" value="Réserver">
    </form>

    <h2>Récapitulatif de la réservation :</h2>
    <div id="recapReservation">
        <!-- Les détails de la réservation seront ajoutés ici via JavaScript -->
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // JavaScript (identique à celui fourni précédemment) pour gérer l'affichage de la carte et composer l'itinéraire
        // ...

        // Ajouter un écouteur d'événement pour le formulaire d'acheter des services complémentaires
        var formAcheterServices = document.getElementById('formAcheterServices');
        formAcheterServices.addEventListener('submit', function(event) {
            event.preventDefault();

            // Récupérer les valeurs sélectionnées dans le formulaire
            var pointArretId = formAcheterServices.elements['point_arret_id'].value;
            var nombreBagages = formAcheterServices.elements['nombre_bagages'].value;

            // Envoyer les données au serveur pour acheter les services complémentaires (implémentation côté serveur nécessaire)
            // ...

            // Afficher un message de confirmation (exemple)
            var recapReservation = document.getElementById('recapReservation');
            recapReservation.innerHTML = "Vous avez acheté " + nombreBagages + " services complémentaires pour le point d'arrêt avec l'ID " + pointArretId + ".";
        });

        // Ajouter un écouteur d'événement pour le formulaire de réserver un hébergement
        var formReserverHebergement = document.getElementById('formReserverHebergement');
        formReserverHebergement.addEventListener('submit', function(event) {
            event.preventDefault();

            // Récupérer les valeurs sélectionnées dans le formulaire
            var pointArretId = formReserverHebergement.elements['point_arret_id'].value;
            var nombrePersonnes = formReserverHebergement.elements['nombre_personnes'].value;
            var dateArrivee = formReserverHebergement.elements['date_arrivee'].value;

            // Envoyer les données au serveur pour effectuer la réservation de l'hébergement (implémentation côté serveur nécessaire)
            // ...

            // Afficher un message de confirmation (exemple)
            var recapReservation = document.getElementById('recapReservation');
            recapReservation.innerHTML = "Vous avez réservé un hébergement pour " + nombrePersonnes + " personnes au point d'arrêt avec l'ID " + pointArretId + " à la date d'arrivée : " + dateArrivee + ".";
        });
    </script>
</body>
</html>
