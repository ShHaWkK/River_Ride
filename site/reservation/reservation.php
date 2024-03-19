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

$firstReservation = false;

// Vérification de la première réservation
$stmtCheckReservation = $conn->prepare("SELECT COUNT(*) AS reservation_count, first_promo_used FROM reservations r JOIN users u ON r.user_id = u.id WHERE r.user_id = :user_id");
$stmtCheckReservation->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmtCheckReservation->execute();
$reservationInfo = $stmtCheckReservation->fetch(PDO::FETCH_ASSOC);

$reservationCount = $reservationInfo['reservation_count'];
$firstPromoUsed = $reservationInfo['first_promo_used'];

if ($reservationCount === 0 && $firstPromoUsed === 0) {
    $firstReservation = true;
    include 'send_reduction.php';
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
            // Récupérer le logement_id
            $stmtPack = $conn->prepare("SELECT logement_id FROM packs WHERE id = :pack_id");
            $stmtPack->bindParam(':pack_id', $pack_id, PDO::PARAM_INT);
            $stmtPack->execute();
            $pack = $stmtPack->fetch(PDO::FETCH_ASSOC);
            $logement_id = $pack['logement_id'];

            // Vérification de la disponibilité
            $stmtCheckAvailability = $conn->prepare("SELECT COUNT(*) AS count FROM reservations WHERE logement_id = :logement_id AND (
                (date_debut BETWEEN :date_debut AND :date_fin) OR 
                (date_fin BETWEEN :date_debut AND :date_fin) OR
                (date_debut <= :date_debut AND date_fin >= :date_fin)
            )");
            $stmtCheckAvailability->bindParam(':logement_id', $logement_id, PDO::PARAM_INT);
            $stmtCheckAvailability->bindParam(':date_debut', $date_debut, PDO::PARAM_STR);
            $stmtCheckAvailability->bindParam(':date_fin', $date_fin, PDO::PARAM_STR);
            $stmtCheckAvailability->execute();
            $availability = $stmtCheckAvailability->fetch(PDO::FETCH_ASSOC)['count'];

            if ($availability > 0) {
                $message = "Le logement n'est pas disponible aux dates spécifiées!";
            } elseif ($date_debut > $date_fin) {
                $message = "La date de début doit être antérieure à la date de fin!";
            } else {
                // Insertion si toutes les conditions sont remplies
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

                if (!$firstReservation && $stmtReservation->execute()) {
                    header("Location: reservation_etape2.php?reservation_id=" . $conn->lastInsertId());
                    exit;
                } elseif ($firstReservation) {
                    $message = "Félicitations pour votre première réservation! Un e-mail avec un code de réduction a été envoyé.";
                } else {
                    $message = "Erreur lors de la réservation";
                }
            }
        }
    }
} else {
    $message = "ID du pack non fourni! <a href='list_packs.php'>Retour à la liste des packs</a>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réserver un Pack</title>
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
    </style>
</head>
<body>
<div class="container">
    <h1>Réserver un Pack</h1>
    <?php if(isset($message) && $message): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <input type="hidden" name="pack_id" value="<?php echo $pack_id; ?>">
        
        <label for="date_debut">Date de début:</label>
        <input type="date" name="date_debut" required><br><br>
        
        <label for="date_fin">Date de fin:</label>
        <input type="date" name="date_fin" required><br><br>

        <label for="nombre_adultes">Nombre d'adultes:</label>
        <input type="number" name="nombre_adultes" required><br><br>
        
        <label for="nombre_enfants">Nombre d'enfants:</label>
        <input type="number" name="nombre_enfants" required><br><br>
        
        <input type="submit" value="Réserver" class="reserve-btn">
    </form>
</div>
</body>