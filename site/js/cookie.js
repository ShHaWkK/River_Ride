const cookieBox = document.querySelector(".cookie-wrapper"),
  buttons = document.querySelectorAll(".choix");

const executeCodes = () => {
  // Si le cookie contient "gymlight", il sera retourné et le code situé en dessous ne s'exécutera pas.
  if (document.cookie.includes("gymlight")) return;
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
