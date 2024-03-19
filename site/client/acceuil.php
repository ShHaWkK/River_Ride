<?php
session_start(); 




try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}



// Récupération des informations de l'utilisateur
$stmtUser = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$stmtReservations = $conn->prepare("SELECT COUNT(*) as total_reservations FROM reservations WHERE user_id = ?");
$stmtReservations->execute([$_SESSION['user_id']]);
$reservationData = $stmtReservations->fetch(PDO::FETCH_ASSOC);
$totalReservations = $reservationData['total_reservations'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil - Vacances en kayak sur la Loire</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            color: #333;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #333;
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #b3b3b3;
            display: block;
            transition: color 0.3s, background-color 0.3s;
        }

        .sidebar a:hover {
            color: #fff;
            background-color: #444;
        }

        .content {
            margin-left: 260px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        h1, h2 {
            border-bottom: 2px solid #e0e0e0;
            padding-bottom: 10px;
        }

        p {
            line-height: 1.5;
            font-size: 16px;
        }
        
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    width: 80%;
    max-width: 400px;
    background-color: #ffffff;
    border-radius: 5px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}

.modal-content label,
.modal-content input {
    display: block;
    width: 100%;
    margin-bottom: 20px;
}

.modal-content input[type="submit"] {
    cursor: pointer;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.modal-content input[type="submit"]:hover {
    background-color: #45a049;
}

.close {
    position: absolute;
    right: 10px;
    top: 5px;
    cursor: pointer;
}
.content {
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    margin-bottom: 20px;
}

.profile {
    border-top: 1px solid #eaeaea;
    padding-top: 20px;
}

.profile-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eaeaea;
}

.profile-item:last-child {
    border-bottom: none;
}

.label {
    flex: 1;
    font-weight: 500;
    color: #555;
}

.value {
    flex: 2;
    color: #333;
}

.edit-button,
.edit-link {
    background-color: #007BFF;
    color: #fff;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    transition: background-color 0.2s;
    text-decoration: none;
}

.edit-button:hover,
.edit-link:hover {
    background-color: #0056b3;
    text-decoration: none;
}

    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="edit_profile.php">Editer le profil</a>
        <a href="reservations.php">Vos réservations</a>
        <a href="points_arret.php">Voir les points d'arrêt</a>
        <a href="packs.php">Voir nos packs</a>
        <a href="itineraire.php">Créer votre itinéraire</a>
        <a href="itineraire/list_itineraire.php">itineraire Réservé</a>
        
        <a href="logout.php">Se déconnecter</a>
    </div>

    
    <div class="content">
    <h1>Bienvenue, <?php echo $user['prenom'] . ' ' . $user['nom']; ?>!</h1>
    <div class="profile">
        <h2>Votre profil:</h2>
        <div class="profile-item">
            <span class="label">Nom:</span>
            <span class="value"><?php echo $user['nom']; ?></span>
        </div>
        <div class="profile-item">
            <span class="label">Prénom:</span>
            <span class="value"><?php echo $user['prenom']; ?></span>
        </div>
        <div class="profile-item">
            <span class="label">Email:</span>
            <span class="value"><?php echo $user['email']; ?></span>
            <button id="editBtn" class="edit-button">Modifier</button>
        </div>
        <div class="profile-item">
            <span class="label">Mot de passe:</span>
            <span class="value">********</span>
            <a href="information/reset_password.php" class="edit-link">Modifier</a>
        </div>
    </div>
</div>

        
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Modifier les informations</h2>
                <form action="update_info.php" method="POST">
                    <label for="prenom">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required>
                    <label for="nom">Nom:</label>
                    <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                    <input type="submit" value="Mettre à jour">
                </form>
            </div>
        </div>

        <h2>Vos dernières réservations:</h2>
        <?php
        $stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ? ORDER BY date_reservation DESC LIMIT 5");
        $stmt->execute([$_SESSION['user_id']]);
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($reservations as $reservation):
        ?>
            <p><?php echo $reservation['date_reservation']; ?>: <?php echo $reservation['description']; ?></p>
        <?php endforeach; ?>
    </div>
</body>
<script>
    // Get modal and button
    var modal = document.getElementById("myModal");
    var btn = document.getElementById("editBtn");
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks the button, open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</html>
