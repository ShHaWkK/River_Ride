<?php
include 'Connection.php'; // Remplacez 'path_to_your_connection_file.php' par le chemin vers votre fichier de connexion

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $date_debut = $_POST["date_debut"];
    $date_fin = $_POST["date_fin"];
    $pourcentage_reduction = $_POST["pourcentage_reduction"];

    // Ajouter une offre promotionnelle
    $query = $conn->prepare("INSERT INTO offres_promotionnelles (nom, date_debut, date_fin, pourcentage_reduction) VALUES (:nom, :date_debut, :date_fin, :pourcentage_reduction)");
    $query->execute([
        'nom' => $nom,
        'date_debut' => $date_debut,
        'date_fin' => $date_fin,
        'pourcentage_reduction' => $pourcentage_reduction
    ]);

    echo "Offre ajoutée avec succès!";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Offres Kayak</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #e1f5e7; /* Vert clair pour le fond */
            color: #333; /* Couleur foncée pour le texte */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #66bb6a; /* Vert moyen pour l'entête */
            padding: 20px 0;
            text-align: center;
        }

        h1 {
            color: white; /* Titre en blanc pour contraster avec le vert */
            margin: 0;
        }

        h2 {
            border-bottom: 3px solid #66bb6a; /* Bordure verte pour les titres de sections */
            padding-bottom: 10px;
        }

        form {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff; /* Fond blanc pour le formulaire */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre douce pour le formulaire */
        }

        input[type="submit"] {
            background-color: #66bb6a; /* Bouton vert */
            color: white; /* Texte du bouton en blanc */
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #558b62; /* Assombrir le bouton au survol */
        }
    </style>
</head>
<body>
    <header>
        <h1>Offres Kayak</h1>
    </header>
    <section>
        <h2>Ajouter une offre promotionnelle</h2>
        <form action="add_offer.php" method="post">
            <label for="nom">Nom de l'offre:</label>
            <input type="text" name="nom" required><br><br>

            <label for="date_debut">Date de début:</label>
            <input type="date" name="date_debut" required><br><br>

            <label for="date_fin">Date de fin:</label>
            <input type="date" name="date_fin" required><br><br>

            <label for="pourcentage_reduction">Réduction (%):</label>
            <input type="number" name="pourcentage_reduction" step="0.01" required><br><br>

            <input type="submit" value="Ajouter l'offre">
        </form>
    </section>
</body>
</html>

