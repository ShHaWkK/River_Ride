<?php
session_start(); 

if (isset($_POST['pseudo']) AND isset($_POST['mdp']) AND !empty($_POST['pseudo']) AND !empty($_POST['mdp'])) {
	$pseudo_par_defaut = "admin";
	$mdp_par_defaut = "admin1234";
	$pseudo_saisi = htmlspecialchars($_POST['pseudo']); 
	$mdp_saisi = htmlspecialchars($_POST['mdp']);
	
	// Vous devriez ici vérifier si le pseudo et le mot de passe saisi correspondent aux valeurs par défaut
	if ($pseudo_saisi == $pseudo_par_defaut && $mdp_saisi == $mdp_par_defaut) {
	    // Ici, vous pouvez, par exemple, rediriger l'utilisateur vers la page d'accueil de l'administrateur
	} else {
	    // Ici, vous pouvez afficher un message d'erreur disant que le pseudo ou le mot de passe est incorrect
	}
}
?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" 
	<title>Espace de connexion admin </title>
</head>
<body>
	<form method="POST" action="" align="center">
		<label>Pseudo :</label>
		<input type="text" name="pseudo" autocomplete="off">
		<br>
		<label>Mot de passe :</label>
		<input type="password" name="mdp" autocomplete="off">
		<br>
		<input type="submit" name="valider" value="Se connecter">
	</form>
</body>
</html>
