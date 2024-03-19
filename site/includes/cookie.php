<div class="cookie-wrapper">

      <div class="titre">
        <i class="bx bx-cookie"></i>
        <img src="images/cookie.png" alt="Icookie" class="header-image">
        <h2>Cookies</h2>
      </div>

      <div class="data">
        <p>Lorsque vous visitez notre site, nous pouvons accédez à certaines de vos informations pour mieux vous aiguillez et facilitez la tache sur notre site. Pour en savoir plus : Politique de confidentialité <a href="#"> Read more...</a></p>
      </div>

      <div class="choisir">
        <button class="choix" id="acceptBtn">Accepter</button>
        <button class="choix" id="declineBtn">Refuser</button>
      </div>
    </div>
<style>
  /* Google Fonts - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

.cookie-wrapper {
  position: fixed;
  bottom: 50px;
  right: -370px;
  max-width: 345px;
  width: 100%;
  background: #fff;
  border-radius: 8px;
  padding: 15px 25px 22px;
  transition: right 0.3s ease;
  box-shadow: 0 5px 10px rgba(5.4, 4.4, 4.4, 0.5);
}
.cookie-wrapper.show {
  right: 20px;
}
.cookie-wrapper .titre {
  display: flex;
  align-items: center;
  column-gap: 15px;
}
.titre i {
  color:#cde8d6;
  font-size: 32px;
}
.titre h2 {
  color: #cde8d6;
  font-weight: 500;
}
.cookie-wrapper .data {
  margin-top: 16px;
}
.cookie-wrapper .data p {
  color: #333;
  font-size: 16px;
}
.data p a {
  color: #cde8d6;
  text-decoration: none;
}
.data p a:hover {
  text-decoration: underline;
}

.cookie-wrapper .choisir {
  margin-top: 16px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.choisir .choix {
  border: none;
  color: #fff;
  padding: 8px 0;
  border-radius: 4px;
  background:#cde8d6;
  cursor: pointer;
  width: calc(100% / 2 - 10px);
  transition: all 0.2s ease;
}
.choisir #acceptBtn:hover {
  background-color: #68BD38;
}
#declineBtn {
  border: 2px solid #DBB323;
  background-color: #fff;
  color: #DBB323;
}
#declineBtn:hover {
  background-color: #68BD38;
  color: #fff;
}
</style>
    <script>
      const cookieBox = document.querySelector(".cookie-wrapper"),
  buttons = document.querySelectorAll(".choix");

const executeCodes = () => {
  // Si le cookie contient "RiverRide", il sera retourné et le code situé en dessous ne s'exécutera pas.
  if (document.cookie.includes("RiverRide")) return;
  cookieBox.classList.add("show");

  buttons.forEach((button) => {
    button.addEventListener("click", () => {
      cookieBox.classList.remove("show");

      //if button has acceptBtn id
      if (button.id == "acceptBtn") {
        //set cookies for 1 month. 60 = 1 min, 60 = 1 hours, 24 = 1 day, 30 = 30 days
        document.cookie = "cookieBy= gymlight; max-age=" + 60 * 60 * 24 * 30;
      }
    });
  });
};

//executeCodes function will be called on webpage load
window.addEventListener("load", executeCodes);
    </script>