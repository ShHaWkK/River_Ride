<?php
// Commencer la session
session_start();

// Vérifier si l'administrateur est connecté
if(!isset($_SESSION['admin'])){
    header('Location: Admin_login.php'); // Rediriger vers la page de connexion si l'administrateur n'est pas connecté
    exit();
}

include('Connection.php'); // Inclure la connexion à la base de données

// Vérifier si la colonne "role" existe dans la table "users"
$stmt = $conn->prepare('SHOW COLUMNS FROM users WHERE field = "role"');
$stmt->execute();
$column = $stmt->fetch();

if(!$column){
    // La colonne "role" n'existe pas, donc nous la créons avec le rôle "client" par défaut
    $stmt = $conn->prepare('ALTER TABLE users ADD COLUMN role VARCHAR(255) NOT NULL DEFAULT "client"');
    $stmt->execute();
}

// Récupérer les informations de l'administrateur
$stmt = $conn->prepare('SELECT * FROM administrateurs WHERE nom = :nom');
$stmt->execute(array('nom' => $_SESSION['admin']));
$admin = $stmt->fetch();

// Récupérer tous les utilisateurs de la base de données
$stmt = $conn->prepare('SELECT * FROM users');
$stmt->execute();
$users = $stmt->fetchAll();

// Vérifier si l'administrateur a demandé à supprimer un utilisateur
if(isset($_POST['delete_user'])){
    // Supprimer d'abord les itinéraires liés à cet utilisateur
    $stmt = $conn->prepare('DELETE FROM itineraire WHERE user_id = :id');
    $stmt->execute(array('id' => $_POST['delete_user']));

    // Supprimer ensuite les réservations liées à cet utilisateur
    $stmt = $conn->prepare('DELETE FROM reservations WHERE user_id = :id');
    $stmt->execute(array('id' => $_POST['delete_user']));

    // Puis supprimer l'utilisateur
    $stmt = $conn->prepare('DELETE FROM users WHERE id = :id');
    $stmt->execute(array('id' => $_POST['delete_user']));
    header('Location: Admin_Panel.php');
    exit();
}

// Vérifier si l'administrateur a demandé à bannir un utilisateur
if(isset($_POST['ban_user'])){
    $stmt = $conn->prepare('UPDATE users SET banni = 1 WHERE id = :id');
    $stmt->execute(array('id' => $_POST['ban_user']));
    header('Location: Admin_Panel.php');
    exit();
}

// Promouvoir un utilisateur en tant qu'administrateur
if(isset($_POST['promote_user'])){
    $stmt = $conn->prepare('UPDATE users SET role = "admin" WHERE id = :id');
    $stmt->execute(array('id' => $_POST['promote_user']));
    header('Location: Admin_Panel.php'); 
    exit();
}

// Déconnecter l'administrateur
if(isset($_POST['logout'])){
    unset($_SESSION['admin']);
    header('Location: Admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <title>Panneau d'administration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .sidebar {
            background-color: #f1f1f1;
            height: 100vh;
            padding: 20px 0;
        }

        .sidebar a {
            text-decoration: none;
            color: #333;
            padding: 10px 15px;
            display: block;
        }

        .sidebar a:hover {
            background-color: #ddd;
        }

        .content {
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Menu latéral -->
            <div class="col-md-3 sidebar">
                <h3>RiverRide</h3>
                <hr>
                <h5>Menu Administration</h5>
                <ul class="nav flex-column">
                    <li>
                    <a href="#users" class="list-group-item list-group-item-action">
                        <i class="fa fa-users mr-2"></i>Tous les utilisateurs
                    </a>
                    </li>
                    <li>
                    <a href="logement/gestion_logements.php" class="list-group-item list-group-item-action">
                        <i class="fa fa-home mr-2"></i>Gérer les logements
                    </a>
                    </li>
                    <li>
                    <a href="offre/gestion_offer.php" class="list-group-item list-group-item-action">
                         <i class="fa fa-gift mr-2"></i>Offres promotionnelles
                    </a>
                    <li class="nav-item dropdown">
                    <a class="list-group-item list-group-item-action dropdown-toggle" href="#" id="packDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus-square mr-2"></i>Itinéraires
                    </a>
                    <div class="dropdown-menu" aria-labelledby="packDropdown">
                        <a class="dropdown-item" href="itineraire/add_itineraire.php"><i class="fa fa-plus-square mr-2"></i>Ajouter un itinéraire</a>
                        <a class="dropdown-item" href="itineraire/list_itineraire.php"><i class="fa fa-list mr-2"></i>Lister un(les) itinéraire(s)</a>
                        <a class="dropdown-item" href="itineraire/compose_itinerary.php"><i class="fa fa-list mr-2"></i>Composer son Itinéraire</a>
                    </div>
                </li>
                    <li class="nav-item dropdown">
                    <a class="list-group-item list-group-item-action dropdown-toggle" href="#" id="packDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus-square mr-2"></i>Packs
                    </a>
                    <div class="dropdown-menu" aria-labelledby="packDropdown">
                        <a class="dropdown-item" href="packs/create_packs.php"><i class="fa fa-plus-square mr-2"></i>Création de pack</a>
                        <a class="dropdown-item" href="packs/list_pack.php"><i class="fa fa-list mr-2"></i>Lister Pack</a>
                        <a class="dropdown-item" href="packs/add_packs.php"><i class="fa fa-list mr-2"></i>Un Pack pour plusieurs itinéraires</a>
                    </div>
                </li>
                    <li>
                    <a href="reservations/view_reservation.php" class="list-group-item list-group-item-action">
                        <i class="fa fa-calendar mr-2"></i>Réservations
                    </a>
                    </li>
                    <li>
                    <a href="graphique/taux_occupation.php" class="list-group-item list-group-item-action">
                        <i class="fa fa-envelope mr-2"></i>Occupation
                    </a>
                    </li>

                    <li>
                    <a href="Captcha/list_capcha.php" class="list-group-item list-group-item-action">
                        <i class="fa fa-envelope mr-2"></i>Captcha
                    </a>
                    </li>
                </ul>
                <form method="POST" style="margin-top: 20px;">
                    <button type="submit" name="logout" class="btn btn-danger btn-block">Se déconnecter <i class="fa fa-sign-out"></i></button>
                </form>
            </div>

            <!-- Contenu principal -->
            <div class="col-md-9 content">

    <h1>Bienvenue, <?php echo isset($admin['nom']) ? htmlspecialchars($admin['nom']) : 'Administrateur'; ?>!</h1>
    <p>Vous êtes connecté en tant qu'administrateur.</p>

    <!-- Afficher tous les utilisateurs -->
    <h2 id="users">Tous les utilisateurs:</h2>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo isset($user['id']) ? htmlspecialchars($user['id']) : ''; ?></td>
                    <td>
                        <?php echo isset($user['role']) && $user['role'] === 'admin' ? '<i class="fa fa-star text-warning"></i>' : ''; ?>
                        <?php echo isset($user['nom']) ? htmlspecialchars($user['nom']) : ''; ?>
                    </td>
                    <td><?php echo isset($user['prenom']) ? htmlspecialchars($user['prenom']) : ''; ?></td>
                    <td><?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?></td>
                    <td>
                        <form method="POST" class="d-flex">
                            <button type="submit" name="delete_user" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>" class="btn btn-sm btn-danger mr-2">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button type="submit" name="ban_user" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>" class="btn btn-sm btn-warning mr-2">
                                <i class="fa fa-ban"></i>
                            </button>
                            <?php if($user['role'] !== 'admin'): ?>
                            <button type="submit" name="promote_user" value="<?php echo isset($user['id']) ? $user['id'] : ''; ?>" class="btn btn-sm btn-success">
                                <i class="fa fa-arrow-up"></i>
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


</html>
