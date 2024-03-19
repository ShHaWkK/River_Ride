<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mon Site - Footer Design</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .footer {
            background-color: rgb(69, 171, 47);
            color: #fff;
            padding: 50px 0;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            margin: 0 auto;
            max-width: 1200px;
            padding: 0 20px;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
            margin: 0 10px;
        }

        .footer-section h3 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .footer-section p {
            line-height: 1.6;
        }

        .contact-info {
            margin-top: 10px;
        }

        .contact-info p {
            margin: 5px 0;
        }

        .social-icons {
            margin-top: 20px;
        }

        .social-icon {
            font-size: 24px;
            color: #fff;
            margin-right: 10px;
            transition: color 0.3s ease-in-out;
        }

        .social-icon:hover {
            color: #28a745; /* Couleur verte au survol */
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
        }

        .footer-bottom p {
            color: #fff;
            font-size: 14px;
        }

/*Dark Mode */
        .dark-mode {
            background-color: #333;
            color: #fff;
        }

        .dark-mode .footer {
            background-color: #333;
            color: #fff;
        }

        .dark-mode .footer-section h3 {
            color: #fff;
        }

        .dark-mode .social-icon {
            color: #fff;
        }

        .dark-mode .footer-bottom p {
            color: #fff;
        }
/*Media queries */
        @media screen and (max-width: 1200px) {
            .footer-content {
                max-width: 990px;
            }
        }

        @media screen and (max-width: 990px) {
            .footer-content {
                max-width: 750px;
            }
        }
        @media screen and (max-width: 768px) {
            .footer-content {
                flex-direction: column;
            }

            .footer-section {
                margin-bottom: 40px;
            }
        }


    </style>
</head>
<body>
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>À Propos</h3>
                <p>Notre site a pour objectif de gérer des offres de vacances en kayak sur la Loire</p>
            </div>
            <div class="footer-section">
                <h3>Contactez-nous</h3>
                <div class="contact-info">
                    <p><i class="fas fa-envelope"></i> <a href="mailto:riverride573@gmail.com">riverride573@gmail.com</a></p>
                    <p><i class="fas fa-phone"></i> + 33 88 77 66 55</p>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Rue de la Rivière, Ville</p>
                </div>
            </div>
            <div class="footer-section">
                <h3>Liens Utiles</h3>
                <p><a href="#">Accueil</a></p>
                <p><a href="#">Services</a></p>
                <p><a href="#">Galerie</a></p>
                <p><a href="#">Contact</a></p>
            </div>
            <div class="footer-section">
                <h3>Suivez-nous</h3>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f social-icon"></i></a>
                    <a href="#"><i class="fab fa-twitter social-icon"></i></a>
                    <a href="#"><i class="fab fa-instagram social-icon"></i></a>
                    <a href="#"><i class="fab fa-linkedin social-icon"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 RiverRide - Tous droits réservés</p>
        </div>
    </footer>
</body>
</html>
