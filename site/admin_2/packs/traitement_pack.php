<?php
// Commencer la session
session_start();

// Vérifier si l'administrateur est connecté
if(!isset($_SESSION['admin'])){
    header('Location: Admin_login.php'); // Rediriger vers la page de connexion si l'administrateur n'est pas connecté
    exit();
}
include('Connection.php');

// Traitement du formulaire de proposition de pack
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $point_depart_id = $_POST['points_depart'];
    $point_arrivee_id = $_POST['points_arrivee'];

    // Insérez ici le code pour enregistrer les données du pack proposé dans la base de données
    // Vous pouvez également récupérer les hébergements sélectionnés
    if (isset($_POST['hebergements']) && is_array($_POST['hebergements'])) {
        $hebergements = $_POST['hebergements'];
        // $hebergements contient un tableau d'ID d'hébergements sélectionnés
        // Vous pouvez les insérer dans la base de données en fonction de vos besoins
    }

    // Rediriger vers une page de confirmation ou une autre page appropriée
    header('Location: confirmation.php');
    exit();
}
?>
