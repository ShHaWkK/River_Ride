<!DOCTYPE html>
<html>
<head>
    <title>Composer son itinéraire kayak</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h1>Composer son itinéraire kayak</h1>

    <!-- Formulaire pour ajouter un point d'arrêt -->
    <h2>Ajouter un point d'arrêt :</h2>
    <form action="add_point.php" method="post">
        <label for="nom_point">Nom du point d'arrêt :</label>
        <input type="text" name="nom_point" required>
        <br>
        <label for="description_point">Description :</label>
        <textarea name="description_point" required></textarea>
        <br>
        <label for="latitude">Latitude :</label>
        <input type="text" name="latitude" required>
        <br>
        <label for="longitude">Longitude :</label>
        <input type="text" name="longitude" required>
        <br>
        <input type="submit" name="ajouter_point_arret" value="Ajouter">
    </form>

    <!-- Carte Leaflet -->
    <div id="map"></div>

    <!-- Liste des points d'arrêt disponibles -->
    <h2>Points d'arrêt disponibles :</h2>
    <ul>
        <?php foreach ($points_arret as $point) : ?>
            <li><?php echo $point['nom']; ?> (<?php echo $point['latitude']; ?>, <?php echo $point['longitude']; ?>)</li>
        <?php endforeach; ?>
    </ul>

    <!-- Formulaire pour ajouter un hébergement -->
    <h2>Ajouter un hébergement :</h2>
    <form action="add_hebergement.php" method="post">
        <label for="point_arret_id">Point d'arrêt :</label>
        <select name="point_arret_id" required>
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="nom_hebergement">Nom de l'hébergement :</label>
        <input type="text" name="nom_hebergement" required>
        <br>
        <label for="description_hebergement">Description :</label>
        <textarea name="description_hebergement" required></textarea>
        <br>
        <label for="capacite_hebergement">Capacité :</label>
        <input type="number" name="capacite_hebergement" required>
        <br>
        <label for="prix_hebergement">Prix :</label>
        <input type="number" name="prix_hebergement" step="0.01" required>
        <br>
        <input type="submit" name="ajouter_hebergement" value="Ajouter">
    </form>

    <!-- Nouveau formulaire pour le trajet itinéraire kayak -->
    <h2>Créer un trajet itinéraire kayak :</h2>
    <form id="formItineraire">
        <label for="points_depart">Point de départ :</label>
        <select name="points_depart" required>
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="points_arrivee">Point d'arrivée :</label>
        <select name="points_arrivee" required>
            <?php foreach ($points_arret as $point) : ?>
                <option value="<?php echo $point['id']; ?>"><?php echo $point['nom']; ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <input type="submit" value="Créer l'itinéraire">
    </form>

    <h2>Itinéraire kayak :</h2>
    <ul id="itineraireSurFleuve">
        <!-- Les points d'arrêt sélectionnés seront ajoutés ici via JavaScript -->
    </ul>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        var mymap = L.map('map').setView([48.8566, 2.3522], 12); // [Latitude, Longitude], Niveau de zoom

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mymap);

        // Définir une icône spéciale pour représenter le fleuve
        var fleuveIcon = L.divIcon({
            className: 'custom-icon',
            html: '<div style="background-color: blue; width: 10px; height: 30px;"></div>',
            iconSize: [10, 30]
        });

        var pointsArret = <?php echo json_encode($points_arret); ?>;

        // Ajout des marqueurs sur la carte pour chaque point d'arrêt
        pointsArret.forEach(function(point) {
            var lat = parseFloat(point.latitude);
            var lng = parseFloat(point.longitude);
            L.marker([lat, lng]).addTo(mymap).bindPopup(point.nom);
        });

        // Ajouter un écouteur d'événement pour le formulaire d'itinéraire kayak
        var formItineraire = document.getElementById('formItineraire');
        formItineraire.addEventListener('submit', function(event) {
            event.preventDefault();

            // Récupérer les valeurs sélectionnées dans le formulaire
            var pointDepartId = formItineraire.elements['points_depart'].value;
            var pointArriveeId = formItineraire.elements['points_arrivee'].value;

            // Trouver les coordonnées du point de départ et d'arrivée
            var pointDepart = pointsArret.find(function(point) {
                return point.id == pointDepartId;
            });
            var pointArrivee = pointsArret.find(function(point) {
                return point.id == pointArriveeId;
            });

            // Vider l'itinéraire existant
            var itineraireSurFleuveDiv = document.getElementById('itineraireSurFleuve');
            itineraireSurFleuveDiv.innerHTML = '';

            // Dessiner l'itinéraire sur la carte en utilisant une ligne en pointillés (définition des styles)
            var latLngs = [
                [pointDepart.latitude, pointDepart.longitude],
                [pointArrivee.latitude, pointArrivee.longitude]
            ];
            var polyline = L.polyline(latLngs, {
                color: 'blue',
                dashArray: '5, 10' // Ligne en pointillés : alternance de 5 pixels d'espacement et 10 pixels de tracé
            }).addTo(mymap);
            mymap.fitBounds(polyline.getBounds());

            // Afficher l'itinéraire sous la forme de texte
            var itineraireTexte = document.createElement('li');
            itineraireTexte.textContent = "De " + pointDepart.nom + " à " + pointArrivee.nom;
            itineraireSurFleuveDiv.appendChild(itineraireTexte);
        });
    </script>
</body>
</html>
