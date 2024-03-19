<?php
session_start();
include 'Connection.php';

if (isset($_POST['token'])) {
    $submittedCode = $_POST['token'];

    if ($submittedCode == $_SESSION['verification_code']) {
        // Mettre à jour la base de données pour indiquer que l'utilisateur est confirmé
        $id = $_SESSION['id_user'];
        $stmt = $conn->prepare("UPDATE users SET confirme = '1' WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        header("Location: ../index.php");
        exit;
    } else {
        echo "Code incorrect. Veuillez réessayer.";
    }
} else {
    echo "Aucun code soumis.";
}
?>
