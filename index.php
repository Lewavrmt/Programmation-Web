<?php
session_start(); // demarre une nouvelle session ou reprend une deja existante . on stocke les informations de l'utilisateur lorsqu'il se connecte.

require_once 'bib.php'; // inclure le fichier 'bib.php', qui contient nos fonctions que nous avons utiliser dans ce script. c'est une bibliotheque de fonction 

if (is_user_logged_in()) {      // Si l'utilisateur est deja  connecte , il est redirige vers la page de bienvenue.
    redirect_user_to_welcome();
}





$error_message = ""; // $error_message est initialisee avec une chaine vide pour stocker les messages d'erreur lors de la connexion.
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Si formulaire est soumis avec  la methode POST
    $username = $_POST["username"]; // recupere les informations d'identification de l'utilisateur
    $password = $_POST["password"]; // recupere les informations d'identification de l'utilisateur
    if (authenticate_user($username, $password)) { // on utiilse la fonction  authenticate_user() pour savoir si il existe un id relier ase mot de passe 
        redirect_user_to_welcome();
    } else {
        $error_message = "Identifiant ou mot de passe incorrect.";
    }
}


//En resume, ce code est utilise pour creer une page de connexion pour un calendrier 
//collaboratif. Il gere l'authentification de l'utilisateur et affiche un message d'erreur si 
// l'authentification echoue.




?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Calendrier collaboratif</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> 
    <header>
        <h1>Calendrier collaboratif</h1>
    </header>
    <main>
        <h2>Connexion</h2>
        <form action="index.php" method="post">
            <div>
                <label for="username">Identifiant :</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <?php if ($error_message): ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
            <input type="submit" value="Se connecter">
        </form>

        <hr>

        <p>
            Si vous n'êtes pas inscrit, veuillez vous rediriger vers cette page: <li><a href="inscription.php">Inscription</a></li>
        </p>

    </main>
</body>
</html>

