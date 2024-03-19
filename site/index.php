<?php 
session_start();
$title = 'Accueil';
include('includes/head.php');
include('BDD/db.php');

?>
<link rel="stylesheet" type="text/css" href="styles/header.css">
<link rel="stylesheet" type="text/css" href="styles/home.css">
</head>

<body>
<?php include('includes/header2.php'); ?>
<div class="column">
    <div class="widget">
        <div class="widget-container">
            <!-- Style removed -->
        </div>
    </div>
    <div class="widget">
        <div class="widget-container">
            <h1>Vivez la Loire en Kayak</h1>						
        </div>
    </div>
    <div class="widget specialpolice">
        <div class="widget-container">
            <h2>Evadez-vous sur le plus grand fleuve sauvage de France d’avril à septembre</h2>
        </div>
    </div>
    <div class="widget align-center">
        <div class="widget-container">
            <div class="button-wrapper">
                <a href="https://www.loirekayak.com/contact/" class="button-link button-size-xl" role="button">
                    <span class="button-content-wrapper">
                        <span class="button-icon align-icon-left">
                            <i aria-hidden="true" class="far fa-calendar-check"></i>
                        </span>
                        <span class="button-text">Je souhaite réserver</span>
                    </span>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <!-- Section pour les forfaits personnalisés -->
    <div class="recette">
        <img src="images/kayak1.jpg" alt="Balade">
        <h2>Balade matinée</h2>
        <p>C’est le parcours idéal pour débuter, faire une petite balade…</p>
        <a class="btn" href="parcours-personnalise.php">En savoir plus</a>
    </div>
    <div class="recette">
        <img src="images/img_2.jpeg" alt="Balade">
        <h2>Balade après midi</h2>
        <p>Des balades ouvertes à tous pour un après midi à la découverte du plus long fleuve de France...</p>
        <a class="btn" href="parcours-personnalise.php">En savoir plus</a>
    </div>
    <div class="recette">
        <img src="images/la-charite.jpg" alt="Balade">
        <h2>Balade journée</h2>
        <p>Partez à la découverte de la Loire sur une journée et profitez de ces moments uniques en kayak au milieu de la nature...</p>
        <a class="btn" href="Balade/balade.php">En savoir plus</a>
    </div>
    
    <div class="row">
    <div class="column">
        <h2>Explorez la beauté de la Loire en kayak et terminez votre week-end par un pique-nique local</h2>
        <p>Après avoir savouré un délicieux petit déjeuner composé de produits locaux, échangez vos vélos contre des canoës-kayaks et naviguez sur la Loire jusqu'à la base de notre centre Kayak Loire jusqu'à Ambroise.</p>
        <p>Envie de déjeuner ? Profitez du pique-nique du terroir qui vous a été préparé le matin dans votre hébergement pour reprendre des forces à midi tout en restant au sec à bord !</p>
        <p>Une fois arrivé à Oudon, profitez d'une visite du village et de son château, ou traversez la Loire et montez jusqu'au promontoire du Champalud à Champtoceaux pour admirer la vue panoramique sur le fleuve.</p>
    </div>
    <div class="column">
        <img src="https://www.locationkayak-loire.com/wp-content/uploads/2022/03/descente-crepuscule-canoe.jpg" alt="descente-crepuscule-canoe">
    </div>
</div>



    <!-- Section pour les forfaits préconstruits -->
    <div class="recette">
        <img src="../images/forfait-preconstruit.jpg" alt="Forfait préconstruit">
        <h2>Choisissez un pack</h2>
        <a class="btn" href="packs/list_packs.php">En savoir plus</a>
    </div>

    <!-- Section pour les points d'arrêt -->
    <div class="recette">
        <img src="../images/points-darret.jpg" alt="Points d'arrêt">
        <h2>Nos points d'arrêt</h2>
        <a class="btn" href="arret/arret_page.php">En savoir plus</a>
    </div>

    <div class="recette">
        <img src="../images/points-darret.jpg" alt="Points d'arrêt">
        <h2>Nos Logements</h2>
        <a class="btn" href="logement/select_date.php">En savoir plus</a>
    </div>
</div>

<?php include('includes/cookie.php'); ?>
<?php include('includes/footer.php'); ?>
</body>

</html>
<style>body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 0 25px 25px;
    margin: 0;
    --widgets-spacing: 20px;
    width: calc(100% + var(--widgets-spacing));
}

.recette {
    flex-basis: calc(33.33% - 20px); /* 33.33% for 3 items, 20px is the margin */
    margin: 10px;
    padding: 30px;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    text-align: center;
    align-content: flex-start;
    align-items: flex-start;
    transition: 0.3s;
    background-color: #74B4D159; /* Added this line */
}

.recette:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.recette a {
    display: block;
    text-decoration: none;
    color: #000;
}

.recette img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.recette h2 {
    margin: 0;
    padding: 15px 50px;
    background-color: #74B4D159; /* Added this line */
    border-radius: 0px;
    font-weight: 900;
}
.row {
  display: flex;
  justify-content: center;
}

.column {
  flex-basis: 50%;
}

.column h2 {
  margin: 0;
  padding: 0;
  color: #4361ee !important;
}
.column p {
  margin: 0;
  padding: 0;
}

.column img {
  width: 100%;
  height: auto;
}


footer {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
    margin-top: 40px;
    text-align: center;
}

footer a {
    color: #fff;
}

footer a:hover {
    color: #ccc;
}

/* CSS */
.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 20px;
    background-color: rgb(128, 219, 0);
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: rgb(128, 219, 0);
}



.elementor-9355 .elementor-element.elementor-element-5fb87f6b {
    text-align: center;
    color: #FFFFFF;
    font-size: 115px;
    font-weight: 700;
    line-height: 1em;
    text-shadow: 0px 0px 30px #000000;
}


@media (min-width: 768px) {
    .elementor-column.elementor-col-100,
    .elementor-column[data-col="100"] {
        width: 100%;
    }
}
/* Réglages pour les mobiles */
@media (max-width: 480px) {
    .recette {
        flex-basis: calc(100% - 20px); /* 100% for 1 item, 20px is the margin */
    }
}

@media (min-width: 981px) {
  .row {
    padding: 2% 0;
  }
    .column {
        padding: 2% 0;
    }
    .column img {
        width: 100%;
        height: auto;
    }
}
</style>