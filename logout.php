<?php
session_start(); // demarre une nouvelle session ou reprend une deja existante . on stocke les informations de l'utilisateur lorsqu'il se connecte.
session_destroy(); //detruit la session actuelle, supprimant ainsi toutes les informations stockees dans la session. Cela a pour effet de deconnecter l'utilisateur.
header("Location: index.php"); // instruction redirige l'utilisateur vers la page d'accueil (index.php)
exit; // garentie qu'aucun autre code ne sera execute apres la redirection.
?>