<?php
session_start(); // demarre une nouvelle session ou reprend une deja existante . on stocke les informations de l'utilisateur lorsqu'il se connecte.

require_once 'bib.php'; // inclure le fichier 'bib.php', qui contient nos fonctions que nous avons utiliser dans ce script. c'est une bibliotheque de fonction

if (!is_user_logged_in()) {
    header("Location: index.php");    // Si l'utilisateur n'est pas connecte, il est redirige vers la page d'accueil index.php
    exit;
}

$user_role = get_user_role($_SESSION["username"]); 
// La variable $user_role recupere le role de l'utilisateur et la variable de session $_SESSION["username"] en utilisant la fonction get_user_role() 



/* En resume, ce code cree la page de bienvenue pour les utilisateurs connectes du calendrier collaboratif.
 La page affiche des informations sur l'utilisateur, tels que son nom d'utilisateur et son role, et propose 
 des liens pour acceder aux differentes fonctionnalites en fonction de son role.
*/


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Calendrier collaboratif</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur le calendrier collaboratif</h1>
    </header>
    <main>
        <p>Bonjour, <?= $_SESSION["username"] ?> !</p>
        <p>Votre rôle est : <?= $user_role ?></p>
        <a href="calendar.php">Accéder au calendrier de la semaine en cours</a>   <!-- lien pour acceder au calendrier de la semaine en cours.-->
        <?php if ($user_role == "responsable" || $user_role == "coordinateur"): ?>  
            <a href="list_editor.php">Éditer les listes (matières, enseignants, salles, etc.)</a>
            <!-- Si le role de l'utilisateur est "responsable" ou "coordinateur",lien supplementaire est affiche pour acceder a l'editeur des listes (matieres, enseignants, salles, etc.).-->
        <?php endif; ?>
    </main>
</body>
</html>