<!DOCTYPE html>
<html>
<head>
    <title>Carte des itinéraires</title>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <style>
        #map {
            height: 500px;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script>
        var latitudeDepart = 47.330599; // Latitude du point de départ
        var longitudeDepart = -1.167605; // Longitude du point de départ
        var latitudeArrivee = 47.355509; // Latitude du point d'arrivée
        var longitudeArrivee = -1.015779; // Longitude du point d'arrivée
        var zoomLevel = 12; // Niveau de zoom initial

        var map = L.map('map').setView([latitudeDepart, longitudeDepart], zoomLevel);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var startPoint = L.marker([latitudeDepart, longitudeDepart]).addTo(map)
            .bindPopup("Point de départ: Rue des Mauges, 49530 Orée d'Anjou, Pays de la Loire, France");

        var endPoint = L.marker([latitudeArrivee, longitudeArrivee]).addTo(map)
            .bindPopup("Point d'arrivée: D 752, 49410 Mauges-sur-Loire, Pays de la Loire, France");

        startPoint.openPopup();
        endPoint.openPopup();
    </script>
</body>
</html>
