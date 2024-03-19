<?php
// Fichier db.php

try 
{
	$conn = new PDO('mysql:host=51.77.157.224;dbname=gymlight', 'prisk', 'prisca', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
}
catch(PDOException $e){
	die('Erreur : ' . $e->getMessage());
}


// Vérifier si le point d'arrêt est spécifié dans l'URL (vous pouvez récupérer cette valeur à partir de la requête HTTP)
if (isset($_GET['point_arret_id'])) {
    $point_arret_id = $_GET['point_arret_id'];

    try {
        // Préparer la requête SQL pour récupérer les hébergements associés au point d'arrêt spécifié
        $stmt = $conn->prepare("SELECT * FROM hebergements WHERE point_arret_id = :point_arret_id");

        // Lier le paramètre :point_arret_id avec la valeur $point_arret_id
        $stmt->bindParam(':point_arret_id', $point_arret_id);

        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Afficher les hébergements
        if (count($result) > 0) {
            echo "<h2>Hébergements disponibles à ce point d'arrêt :</h2>";
            echo "<ul>";
            foreach ($result as $row) {
                echo "<li>";
                echo "<p>Nom : " . $row['nom'] . "</p>";
                echo "<p>Description : " . $row['description'] . "</p>";
                echo "<p>Capacité : " . $row['capacite'] . " personnes</p>";
                echo "<p>Prix : " . $row['prix'] . " €</p>";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "Aucun hébergement trouvé pour ce point d'arrêt.";
        }
    } catch (PDOException $e) {
        echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
    }
} else {
    echo "Point d'arrêt non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Projeto Sidebar</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/style.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/f223593715.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrap">
        <div id="sidebar">
            <!-- <div class="btn_close_sidebar">
                <i class="fas fa-times"></i>
            </div> -->
            <div class="sidebar_user">
                <a href="user">
                    <img width="90" src="assets/images/avatar2.png">
                </a>
            </div>
            <div class="sidebar_user_name">
                <p>Marcos Lj</p>
            </div>
            <div class="sidebar-content">
                <ul class="sidebar_nav">
                    <li><a target="_blank" href="lien1"><i class="fas fa-home"></i> Vos Hébergements</a></li>
                    <li><a target="_blank" href="lien2"><i class="fas fa-shopping-cart"></i> Vos Achats</a></li>
                    <li><a target="_blank" href="lien3"><i class="fas fa-cog"></i> Vos Paramètres</a></li>
                    <li><a target="_blank" href="lien4"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
                </ul>
            </div>
        </div>
        <div id="site-content">
            <div class="navbar-top">
                <div class="nav_top_box1">
                    <span class="btn fas fa-align-left" id="open_close"></span>
                    <a href="#">River Ride</a>
                </div>
                <div class="navtop">
                    <ul>
                        <li><i class="fas fa-bell"></i></li>
                        <li><i class="fas fa-envelope"></i></li>
                    </ul>
                </div>
            </div>
            <section class="box_content_site">
                
      <h1>Bienvenue <?php echo $user['prenom'] . " " . $user['nom'];?> sur votre Compte Office</h1>
      <div class="user-profile">
        <div class="avatar">
          <img src="avatar.png" alt="Avatar">
        </div>
        <div class="user-info">
          <h3><?php echo $user['prenom'] . " " . $user['nom'];?> </h3>
          <p>ID: <?php echo $user['id_user'];?></p>
        </div>
      </div>
      
      <div>
            </section>

        </div>
    </div>
  
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('open_close').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('site-content').classList.toggle('active');
            });
        });
    </script>
</body>
</html>

<style>
  * {
  margin: 0;
  padding: 0;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  font-family: 'Roboto', sans-serif;
}

body, html {
  width: 100%;
  height: 100%;
}

a {
  text-decoration: none;
}

ul li {
  list-style: none;
  list-style-position: inside;
}

.wrap {
  width: 100%;
  display: flex;
}

#sidebar {
  width: 250px;
  min-height: 100vh;
  background-color: #022f0b; /* Couleur du menu latéral */
  transition: all 0.3s;
}

#sidebar.active {
  margin-left: -250px;
}

#site-content {
  width: calc(100% - 250px);
  min-height: 100vh;
  background-color: #f2f2f2; /* Couleur du contenu principal */
  transition: all 0.3s;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

#site-content.active {
  width: 100%;
}

/* NAV TOPO */
.navbar-top {
  width: 100%;
  height: 60px;
  background-color: #022f0b;
  color: #fff;
  z-index: 999;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 1px 1px 3px #050505;
}

.nav_top_box1 a {
  color: #fff; /* Couleur du lien dans la barre de navigation supérieure */
  font-size: 1.8rem;
  font-weight: bold;
  margin-left: 1rem;
  transition: all 0.3s;
}

.nav_top_box1 a:hover {
  padding-left: 2rem;
  color: #ff0000; /* Couleur du lien au survol dans la barre de navigation supérieure */
}

.btn {
  font-size: 2rem;
  cursor: pointer;
}

.navtop ul li {
  display: inline-block;
  padding: 20px;
  font-size: 1rem;
}

.box_content_site {
  width: 100%;
  max-width: 1020px;
  margin: 0 auto;
}

.box_content_site p {
  margin: 1rem;
}

/* SIDEBAR NAV */
.sidebar-content {
  margin-top: 200px;
}

.sidebar-content ul {
  width: 100%;
}

.sidebar-content ul li a:first-child {
  border-top: 1px solid #333;
}

.sidebar-content ul li a {
  width: 100%;
  display: block;
  padding: 1rem 1rem 1rem 2rem;
  color: #fff;
  box-shadow: 1px 1px 3px #050505;
  transition: all 0.3s;
}

.sidebar-content ul li a:hover {
  padding-left: 4rem;
  border-left: 4px solid #00ff28; /* Couleur du lien au survol dans le menu latéral */
}

.sidebar_user, .sidebar_user_name {
  position: relative;
}

.sidebar_user img {
  border-radius: 50%;
  position: absolute;
  top: 35px;
  left: 30%;
}

.sidebar_user_name p {
  position: absolute;
  top: 150px;
  left: 26%;
  color: #fff;
  font-size: 1.5rem;
}

.btn_close_sidebar {
  width: 95%;
  height: 100px;
  color: #fff;
  height: 60px;
  margin: 5px;
  font-size: 1.8rem;
  border-radius: 6px;
  background-color: #ff0000;
  display: flex;
  align-items: center;
  justify-content: center;
}


/* RESPOSIVE */
@media (max-width: 1024px) {
  #sidebar {
    margin-left: -250px;
  }
  #sidebar.active {
    margin-left: 0;
  }
  #site-content {
    width: 100%;
  }
  #site-content.active {
    width: calc(100% - 250px);
  }
}
</style>
