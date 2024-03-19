

<header>
    <div class="top-bar">
        <span><ion-icon name="call-outline"></ion-icon> 06 34 57 65 52 </span>
        <div class="search">
            <form action="#" method="GET">
                <input type="text" name="search" placeholder="Rechercher...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

        <ul>
            <li><a href="#"><ion-icon name="logo-facebook"></ion-icon></a></li>
            <li><a href="#"><ion-icon name="logo-twitter"></ion-icon></a></li>
            <li><a href="#"><ion-icon name="logo-instagram"></ion-icon></a></li>
        </ul>
    </div>
    <nav>
        <div class="logo">
            <a href="#">
                <img src="images/logo.png" alt="Logo du site" class="logo-image">
            </a>
        </div>
        <div class="toggle">
            <a href="#"><ion-icon name="menu-outline"></ion-icon></a>
        </div>
        <ul class="menu">
            <li><a href="">Accueil</a></li>
            <li><a href="">Itinéraires</a></li>
            <li><a href="">Tarifs</a></li>
            <li><a href="">Hébergements</a></li>
            <li><a href="">Nous contacter</a></li>
        </ul>
    </nav>
</header>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/cesiumjs/1.78/Build/Cesium/Cesium.js"></script>
    <script>
        $(function(){
            $(".toggle").on("click", function(){
                if($(".menu").hasClass("active")){
                    $(".menu").removeClass("active");
                    $(this).find("a").html("<ion-icon name='menu-outline'></ion-icon>");
                } else {
                    $(".menu").addClass("active");
                    $(this).find("a").html("<ion-icon name='close-outline'></ion-icon>");
                }
            });
        });
    </script>
</body>
</html>