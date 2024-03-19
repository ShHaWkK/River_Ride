<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
if (isset($_SESSION['email'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Handle the case where the email key is not set in the session.
    // This could include redirecting to a login page or showing an error message.
}

$users = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<header>
    <div class="icon"><b style="color: red;">River</b>Ride</div>
    <div class="group">
        <ul class="navigation">
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="itineraires/itineraire.php">Itinéraires</a></li>
            <li><a href="compose/itineraire.php">Réservation</a></li>
            <li><a href="logement/logement.php">Logement</a></li>
            <li><button class="modeToggle">Dark Mode</button></li>

            <li><a href="packs/list_packs.php" class="Packs-button">Packs</a></li>
            <li class="dropdown">
                <a href="#" class="dropbtn"><ion-icon name="person-outline"></ion-icon>Compte</a>
                <div class="dropdown-content">
                <?php 
    if (isset($_SESSION['email'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$_SESSION['email']]);
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    // Handle the case where the email key is not set in the session.
}



                        if ($users !== false) {
                            // Récupère l'heure actuelle (format 24 heures)
                            $currentHour = date('H'); 
                            // Détermine si c'est le matin/jour ou le soir
                            $greeting = ($currentHour < 16) ? "Bonjour" : "Bonsoir";
                        }
                    ?>

                    
                  <?php if (isset($users) && $users !== false): ?>
                    <a href="../client\compte.php">
                    <?php echo $greeting . ", " . $users['prenom']; ?> 
                    </a>
                    <?php
                    echo '<a href="client/acceuil.php">Mon profil</a>';
                    echo '<a href="/Projet River Ride/site/includes/deconnexion.php">Déconnexion</a>
                    ';
                    ?>
                  <?php else: ?>
                    <a href="inscriptionconnexion/inscription_etape1.php">Inscription</a>
                    <a href="inscriptionconnexion/connexion.php">Connexion</a>
                  <?php endif; ?>
                </div>
            </li>
        </ul>
        <div class="search">
          <span class="icon">
            <ion-icon name="search-outline" class="searchBtn"></ion-icon>
            <ion-icon name="close-outline" class="closeBtn"></ion-icon>
          </span>      
        </div>
        <ion-icon name="menu-outline" class="menuToggle"></ion-icon>
      </div>
      <!-- <div class="searchBox">
        <form>
            <input type="text" name="search" placeholder="Rechercher Ici. . .">
            <input type="submit">
        </form>
        
      </div> -->
    </header>
   
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script>
      
  // Sélectionnez le bouton Dark Mode
  let modeToggle = document.querySelector(".modeToggle");
  // Sélectionnez le corps du document
  let body = document.body;

  // Vérifie si le mode sombre est activé dans le stockage local
  let isDarkMode = localStorage.getItem("darkMode");

  if (isDarkMode === "true") {
    body.classList.add("dark-mode");
  }

  // Lorsque le bouton Dark Mode est cliqué
  modeToggle.onclick = function() {
    // Bascule entre le mode sombre et le mode clair
    body.classList.toggle("dark-mode");

    // Stocke l'état actuel du mode sombre dans le stockage local
    if (body.classList.contains("dark-mode")) {
      localStorage.setItem("darkMode", "true");
    } else {
      localStorage.setItem("darkMode", "false");
    }
  }
      modeToggle.onclick = function() {
        // Ajoutez ici les actions que vous souhaitez effectuer lors de la commutation du mode
        document.body.classList.toggle("dark-mode");
      }

      let searchBtn = document.querySelector(".searchBtn");
      let closeBtn = document.querySelector(".closeBtn");
      let searchBox = document.querySelector(".searchBox");
      let navigation = document.querySelector(".navigation");
      let menuToggle = document.querySelector(".menuToggle");
      let header = document.querySelector("header");

      searchBtn.onclick = function(){
        searchBox.classList.add("active");
        closeBtn.classList.add("active");
        searchBtn.classList.add("active");
        menuToggle.classList.add("hide");
        header.classList.remove("open");

      }
      closeBtn.onclick = function(){
        searchBox.classList.remove("active");
        closeBtn.classList.remove("active");
        searchBtn.classList.remove("active");
        menuToggle.classList.remove("hide");
      }
      menuToggle.onclick = function(){
        searchBox.classList.remove("active");
        closeBtn.classList.remove("active");
        searchBtn.classList.remove("active");
        navigation.classList.toggle("active");
        menuToggle.classList.toggle("active");
        header.classList.toggle("open");
      }


      
    </script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

body {
  background: #dee1e2;
  min-height: 100vh;
  overflow-x: hidden;
}
body.dark-mode {
  background: #333;
  color: #eee;
}
header {
  position: relative;
  top:0; 
  left: 0;
  width: 100%;
  height: 80px;
  background: #fff;
  padding: 20px 40px; 
  display: flex;
  justify-content: space-between;
  align-items: center;
 box-shadow: 0 15px 15px rgba(0,0,0,0.05);
}

.logo {
  color: #333;
  text-decoration: none;
  font-size: 1.5em;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.1em;
}

.group {
  display: flex;
  align-items: center;
}

header ul {
  position: relative;
  display: flex;
  gap: 30px;
}

header ul li {
  list-style: none;
}

header ul li a {
  position: relative;
  text-decoration: none;
  color: #333;
  font-size: 1em;
  text-transform: uppercase;
  letter-spacing: 0.2em;
}

header ul li a::before {
  content: '';
  position: absolute;
  bottom: -2px;
  width: 100%;
  height: 2px;
  background: #333;
  transform: scaleX(0);
  transform-origin: right;
  transition: none;
}


header ul li a:hover:before {
  transform: scaleX(0.85);
  transform-origin: left;
  transition: none; 
}
/* @media (min-width: 1280px) and (max-width: 1280px) {
  header ul li a::before {
    transition: none;
    transform: scaleX(0.95);
  }

  header ul li a:hover:before {
    transition: none; 
    transform: scaleX(0.95);
  }
} */
header .search {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 1.5em;
  z-index: 10;
  cursor: pointer;
}

.searchBox {
  position: absolute;
  right: -100%;
  width: 100%;
  height: 100%;
  display: flex;
  background: #fff;
  align-items: center;
  padding: 0 30px;
  transition: .5s ease-in-out;
}

.searchBox.active {
  right: 0;
}
.searchBox input {
  width: 100%;
  border: none;
  outline: none;
  height: 50px;
  color: #fff;
  font-size: 1.25em;
  background: #000;
  border-bottom: 1px solid rgba(255, 255, 255, 0.5);
}

.searchBtn {
  position: relative;
  left: 30px;
  top: 2.5px;
  transition: .5s ease-in-out;
}

.searchBtn.active {
  left: 0;
}

.closeBtn {
opacity: 0;
visibility: hidden;
transition: .5s;
scale: 0;
}

.closeBtn.active {
  opacity: 1;
  visibility: visible;
  transition: .5s;
  scale: 1;

}

.menuToggle{
  position: relative;
  display: none;

}
/* Style pour le bouton déroulant */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Style pour le bouton déroulant */
.dropdown-content {
  display: none;
  position: absolute;
  min-width: 60px;
  left: 0; 
  z-index: 1;
  background-color: #f9f9f9;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

/*  menu déroulant */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}
.dropdown:hover .dropdown-content {
  display: block;
}

/* Style pour le bouton déroulant */
.dropbtn {
  font-size: 1em;
  text-transform: uppercase;
  letter-spacing: 0.2em;
  background-color: transparent;
  color: #333;
  border: none;
  cursor: pointer;
}

.Packs-button {
  background-color: rgb(0, 0, 0); 
  color: white; 
  padding: 5px 10px; 
  border-radius: 3px; 
  text-decoration: none;
}

.reservation-button:hover {
  background-color: rgb(128, 219, 0); 

}

header ul li a.account-icon::before {
  content: '\f007'; 
  font-family: 'Font Awesome 5 Free'; 
  font-weight: 900; 
  margin-right: 5px; 
}


/*                 Responsive                  */
@media (max-width: 990px)
{
  .searchBtn{
    left: 0;
  }
  .menuToggle{
    position: absolute;
    display: block;
    font-size: 2em;
    cursor: pointer;
    transform: translateX(30px);
    z-index: 10;
  }
  header .navigation {
    position: absolute;
   opacity: 0;
    visibility: hidden;
    left: 100%;

  }
  header.open .navigation {
    top: 80px;
    opacity: 1;
    visibility: visible;
    left: 0;
    display: flex;
    flex-direction: column;
    transition: .5s;
    background: #fff;
    width: 100%;
    height: calc(100vh - 80px);
    padding: 40px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
  }
  header.open .navigation li a 
  {
    font-size: 1.25em;
    
  }

  .hide {
    display: none;
  }

}
@media only screen and (max-width: 600px) {
    .navigation {
        display: none; /* Cacher la navigation principale */
    }

    .menuToggle {
        display: block; /* Montrer le bouton du menu pour les mobiles */
    }

    .searchBox {
        width: 100%;
    }
}

/*                 Dark Mode                  */

/* Les styles de base pour le mode lumineux (vous pouvez déjà les avoir) */
body, .icon, .navigation a, .dropdown-content a {
    color: #333;
    background: #FFF;
}

body.dark-mode, .dark-mode .icon, .dark-mode .navigation a, .dark-mode .dropdown-content a {
    color: #f1f1f1;
    background: #000;
}

/* .dark-mode .search {
    background: #444; /* Vous pouvez choisir une autre couleur si vous le souhaitez 
}


.dark-mode .searchBox {
    background: #000;
    color: #f1f1f1;
} */

/* Inversez les couleurs des icônes pour qu'ils soient visibles en mode sombre */
.dark-mode ion-icon {
    color: #f1f1f1;
}

/* Si vous voulez que le bouton Dark Mode change également */
.dark-mode .modeToggle {
    background-color: #f1f1f1;
    color: #333;
}
.dark-mode header {
  background: #000;
  box-shadow: 0 15px 15px rgba(0,0,0,0.2); /* Vous pouvez augmenter l'opacité pour une ombre plus prononcée en mode sombre */
}
.dark-mode header.open .navigation {
  background: #000;  /* un ton de gris légèrement plus foncé que #333 */
  border-top: 1px solid rgba(255, 255, 255, 0.1); /* ligne claire en mode sombre */
}
.dark-mode .navigation li a:hover {
    background-color: red;  /* un ton de gris plus foncé pour le mode sombre */
    color: #ddd;  /* un ton de gris plus clair pour le texte en mode sombre */
}

.modeToggle {
  padding: 5px 15px;
  border: none;
  background-color: #000;
  color: #fff;
  cursor: pointer;
  border-radius: 5px;
  transition: background-color 0.3s ease;
}

.modeToggle:hover {
  background-color: #000;
}

body.dark-mode .modeToggle {
  background-color: #eee;
  color: #333;
}

</style>