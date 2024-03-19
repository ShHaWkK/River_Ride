<!DOCTYPE html>
<html>
<head>
    <title>Acheter des services complémentaires - River Ride</title>
    <!-- Lien vers le fichier de style CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Acheter des services complémentaires</h1>
    <p>Veuillez sélectionner les services complémentaires à acheter :</p>
    <label for="nombre_bagages">Nombre de bagages :</label>
    <input type="number" id="nombre_bagages" required>
    <button onclick="acheterServices()">Acheter</button>

    <div id="messageConfirmation">
        <!-- Le message de confirmation sera affiché ici via JavaScript -->
    </div>

    <script src="js/main.js"></script>
</body>
</html>
