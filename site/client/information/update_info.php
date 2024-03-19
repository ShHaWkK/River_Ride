<?php
session_start();



try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connexion échouée : " . $e->getMessage();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["new_email"]) && isset($_POST["new_password"]) && isset($_POST["confirm_password"])) {

        // Vérification que les deux mots de passe correspondent
        if ($_POST["new_password"] === $_POST["confirm_password"]) {

            $new_email = $_POST["new_email"];
            $new_password = password_hash($_POST["new_password"], PASSWORD_DEFAULT); // Hashage du mot de passe pour la sécurité

            try {
                $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
                $stmt->execute([$new_email, $new_password, $_SESSION['user_id']]);
                
                // Mise à jour des données de session
                $_SESSION['user_email'] = $new_email;
                
                header("Location: ../index.php?message=updated");
                exit();
            } catch(PDOException $e) {
                // Gérer les erreurs éventuelles (par exemple, e-mail déjà utilisé)
                echo "Erreur lors de la mise à jour : " . $e->getMessage();
            }

        } else {
            echo "Les mots de passe ne correspondent pas.";
        }
    }
}
?>
