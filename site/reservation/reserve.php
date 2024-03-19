<?php
session_start();

include 'Connection.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

$message = "";

if (!isset($_SESSION['user_id'])) {
    die("Veuillez vous connecter pour faire une réservation");
    header("Location: ../inscriptionconnexion/connexion.php");
    exit;
}

$pack_id = null;
if (isset($_GET['pack_id']) && !empty($_GET['pack_id'])) {
    $pack_id = intval($_GET['pack_id']);
} elseif (isset($_POST['pack_id']) && !empty($_POST['pack_id'])) {
    $pack_id = intval($_POST['pack_id']);
}

if ($pack_id !== null) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : null;
        $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : null;
        $nombre_adultes = isset($_POST['nombre_adultes']) ? intval($_POST['nombre_adultes']) : 0;
        $nombre_enfants = isset($_POST['nombre_enfants']) ? intval($_POST['nombre_enfants']) : 0;

        if (empty($date_debut) || empty($date_fin)) {
            $message = "Veuillez fournir des dates de début et de fin valides!";
        } else {
            // Récupérer le logement_id associé au pack depuis la base de données
            $stmtPack = $conn->prepare("SELECT logement_id FROM packs WHERE id = :pack_id");
            $stmtPack->bindParam(':pack_id', $pack_id, PDO::PARAM_INT);
            $stmtPack->execute();
            $pack = $stmtPack->fetch(PDO::FETCH_ASSOC);
            $logement_id = $pack['logement_id'];

            // Vérifications de disponibilité, capacité, etc.

            // Vérification des dates
            if ($date_debut > $date_fin) {
                $message = "La date de début doit être antérieure à la date de fin!";
            } else {
                // Insérer la réservation dans la base de données
                try {
                    $stmtReservation = $conn->prepare("INSERT INTO reservations (pack_id, user_id, logement_id, nombre_adultes, nombre_enfants, date_reservation, date_debut, date_fin) VALUES (:pack_id, :user_id, :logement_id, :nombre_adultes, :nombre_enfants, :date_reservation, :date_debut, :date_fin)");

                    $currentDate = date('Y-m-d');
                    $stmtReservation->bindParam(':pack_id', $pack_id, PDO::PARAM_INT);
                    $stmtReservation->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmtReservation->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
                    $stmtReservation->bindParam(':nombre_adultes', $nombre_adultes, PDO::PARAM_INT);
                    $stmtReservation->bindParam(':nombre_enfants', $nombre_enfants, PDO::PARAM_INT);
                    $stmtReservation->bindParam(':date_reservation', $currentDate, PDO::PARAM_STR);
                    $stmtReservation->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
                    $stmtReservation->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);

                    if ($stmtReservation->execute()) {
                        header("Location: mode_paiement.php?reservation_id=" . $conn->lastInsertId());
                        exit;
                    } else {
                        $message = "Erreur lors de la réservation";
                    }
                } catch (PDOException $e) {
                    $message = "Erreur lors de la réservation: " . $e->getMessage();
                }
            }
            $service_complementaire_id = isset($_POST['service_complementaire_id']) ? $_POST['service_complementaire_id'] : null;

if ($service_complementaire_id) {
    // Stocker $service_complementaire_id dans la base de données dans la colonne correspondante de la table de réservations
}

// Vérification de la première réservation et application de la réduction
if (!hasPreviousReservations($_SESSION['user_id'])) {
    $promo_code = 'FIRSTBOOKING'; // Code de réduction pour la première réservation
    if (verifyDiscountCode($_SESSION['user_id'], $promo_code)) {
        $promo_reduction = getDiscountAmount($promo_code);
        $total_amount -= $promo_reduction;
    }
}
        }
    }
} else {
    $message = "ID du pack non fourni! <a href='list_packs.php'>Retour à la liste des packs</a>";
}
?>
<?php
include ('../includes/header2.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3faf3;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #cde8d6;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #2e5d34;
        }

        .reserve-btn {
            background-color: #2e5d34;
            color: #cde8d6;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .reserve-btn:hover {
            background-color: #24472b;
        }

        .message {
            background-color: #f9c9c3;
            padding: 10px;
            border-radius: 5px;
            margin: 20px 0;
            color: #a82515;
            text-align: center;
        }
        .reserve-btn {
        background-color: #2e5d34;
        color: #cde8d6;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s;
}

.reserve-btn:hover {
    background-color: #24472b;
}

.message {
    background-color: #f9c9c3;
    padding: 10px;
    border-radius: 5px;
    margin: 20px 0;
    color: #a82515;
    text-align: center;
}
    </style>
</head>
<body>
<div class="container">
<h1>Réserver un Pack</h1>
    <?php if(isset($message) && $message): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <form action="reserve.php" method="post">
        <input type="hidden" name="pack_id" value="<?php echo $pack_id; ?>">
        
        <label for="date_debut">Date de début:</label>
        <input type="date" name="date_debut" required><br><br>
        
        <label for="date_fin">Date de fin:</label>
        <input type="date" name="date_fin" required><br><br>

        <label for="nombre_adultes">Nombre d'adultes:</label>
        <input type="number" name="nombre_adultes" required><br><br>
        
        <label for="nombre_enfants">Nombre d'enfants:</label>
        <input type="number" name="nombre_enfants" required><br><br>
        
        <label for="service_complementaire">Service complémentaire :</label>
            <select name="service_complementaire">
                <option value="0">Aucun</option>
                <option value="1">Transport des bagages (+10€)</option>
            </select>
        
        <button type="submit" class="reserve-btn" id="reserve-button">Réserver</button>
</form>
</div>
</body>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var reserveButton = document.getElementById('reserve-button');
    
    reserveButton.addEventListener('click', function(e) {
        if (!confirm('Êtes-vous sûr de vouloir réserver ce pack?')) {
            e.preventDefault();
        } else {
            // Rediriger vers la page de mode de paiement après la réservation
            window.location.href = 'mode_paiement.php';
        }
    });
});
</script>
</html>
