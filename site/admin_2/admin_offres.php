<?php
session_start();

// Vérifier si l'administrateur est connecté
if(!isset($_SESSION['admin'])){
    header('Location: Admin_login.php'); // Rediriger vers la page de connexion si l'administrateur n'est pas connecté
    exit();
}

$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations des utilisateurs avec des offres promotionnelles et leur première réservation
    $query = "SELECT u.nom, u.prenom, u.email, r.date_reservation, r.code_reduction_id, cr.code 
              FROM users u
              LEFT JOIN reservations r ON u.id = r.user_id
              LEFT JOIN codes_reduction cr ON r.code_reduction_id = cr.id
              WHERE r.code_reduction_id IS NOT NULL
              ORDER BY r.date_reservation DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $usersWithPromotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Connection échouée : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>River Ride - Administration</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            display: flex;
        }
        /* Menu latéral */
        .sidebar {
            width: 200px;
            background-color: #f1f1f1;
            padding: 10px;
        }
        /* Style des liens du menu */
        .sidebar a {
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            color: #333;
            padding: 5px;
        }
        .sidebar a:hover {
            background-color: #ddd;
        }
        /* Style du contenu principal */
        .content {
            padding: 20px;
        }
        /* Style du titre du contenu */
        .content h1 {
            margin-bottom: 20px;
        }
        /* Style du tableau des utilisateurs */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Bouton de déconnexion */
        .logout-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 sidebar p-3">
                <div class="list-group">
                    <a href="#users" class="list-group-item list-group-item-action">
                        <i class="fa fa-users mr-2"></i>Tous les utilisateurs
                    </a>
                    <a href="admin_gestion_logements.php" class="list-group-item list-group-item-action">
                        <i class="fa fa-home mr-2"></i>Gérer les logements
                    </a>
                    <!-- Other links here... -->
                </div>
                <form method="POST" class="logout-btn mt-4">
                    <button type="submit" name="logout" class="btn btn-danger btn-block">Se déconnecter <i class="fa fa-sign-out"></i></button>
                </form>
            </div>
            <div class="col-md-9 content p-3">
                <h1>Bienvenue, <?php echo isset($admin['nom']) ? htmlspecialchars($admin['nom']) : 'Administrateur'; ?>!</h1>
                <p>Vous êtes connecté en tant qu'administrateur.</p>
                <!-- Afficher tous les utilisateurs -->
                <h2 id="users">Utilisateurs avec des offres promotionnelles:</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Date de réservation</th>
                            <th>Code de réduction</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usersWithPromotions as $user) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['nom']); ?></td>
                            <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['date_reservation']); ?></td>
                            <td><?php echo htmlspecialchars($user['code']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
