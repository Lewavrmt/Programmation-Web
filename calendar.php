<?php
session_start(); // demarre une nouvelle session ou reprend une deja existante . on stocke les informations de l'utilisateur lorsqu'il se connecte.



setlocale(LC_TIME, 'fr_FR.UTF-8'); // Definit le temps et les dates en francais Locale .



require_once 'bib.php'; // Inclusion de la bibliotheque de fonction  'bib.php' 



if (!is_user_logged_in()) {
    header("Location: index.php"); // empeche de se retrouver sur welcomme en tappant directement sur l'URL en gros faut se connecter si c'est pas fait 
    exit;
}



///////////////////////////////////// Initialisation et gestion de la date courante et de l'etat de la session /////////////////////////////////////////////////





$date = new DateTime('Monday this week');  // Cree un objet DateTime pour representer la date du lundi de la semaine en cours.



if (!isset($_SESSION['page_refreshed'])) { // Initialise et verifie l'etat de rafraichissement de la page.
    $_SESSION['page_refreshed'] = false;
}



// Initialise et stocke la date courante en fonction de la navigation.
if (!isset($_SESSION['current_date']) || ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SESSION['page_refreshed'])) {
    $_SESSION['current_date'] = (new DateTime('Monday this week'))->format('Y-m-d');
}


$date = new DateTime($_SESSION['current_date']);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {     // tout sa pour savoir et faiir la difference entre une pge rafrechie et si on appuis sur les boutton + et -
    $_SESSION['page_refreshed'] = false;
} else {
    $_SESSION['page_refreshed'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  // modifie la date + 1 et -1 lorceque on appuis sur les boutton + et - 
    if (isset($_POST['prev-week'])) {
        $date->modify('-1 week');
    } else if (isset($_POST['next-week'])) {
        $date->modify('+1 week');
    }
    $_SESSION['current_date'] = $date->format('Y-m-d');
}


$user_role = get_user_role($_SESSION["username"]); // Obtient le role de l'utilisateur actuellement connecte et l'affecte a user_role.



?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier - Calendrier collaboratif</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <h1>Calendrier collaboratif - Semaine <?= $date->format('Y-m-d') ?></h1>
    <form method="post" action="logout.php" id="logout-form">
        <button type="submit">Déconnexion</button>
    </form>
    
</head>
<body data-role="<?= $user_role ?>">
    <header>
        <h1>Calendrier collaboratif - Semaine <?= $date->format('Y-m-d') ?></h1>
    </header>
    <main>
        <div class="week-navigation">  <!-- afichage des boutton + et - dans un formulaire --> 
        <form method="post" action="calendar.php">
            <button id="prev-week" type="submit" name="prev-week">  &larr; Semaine precedente </button>
            <button id="next-week" type="submit" name="next-week"> Semaine suivante &rarr; </button>
        </form>

        </div>
        <div id="content">
        <?php generateTable($date); ?>   <!-- Genere le tableau du calendrier en utilisant la fonction generateTable() et la date courante --> 
        </div>
    </main>

    <!-- Un formulaire <form> cache (id="popup-form") qui apparaitra lorsqu'un utilisateur ajoutera ou 
    modifiera un evenement. Il contient plusieurs champs pour entrer les informations de l'evenement, 
    tels que la date, le cours, le professeur, le type d'evenement, le groupe, la salle et les heures 
    de debut et de fin. Il y a aussi deux boutons, un pour confirmer et un pour annuler.-->

    <div id="popup-form" class="hidden">
    <form method="post" id="slot-form">
        <label for="date">Date:</label>
        <input type="date" id="date" required>
        <label for="cours">Cours:</label>
        <input type="text" id="cours" required>
        <label for="prof">Prof:</label>
        <input type="text" id="prof" required>
        <label for="type">Type:</label>
            <select id="type" required>
                <option value="CM">CM</option>
                <option value="TD">TD</option>
                <option value="TP">TP</option>
                <option value="Exam">Exam</option>
            </select>
        <label for="groupe">Groupe:</label>
            <select id="groupe" required>
                <option value="1">Groupe 1</option>
                <option value="2">Groupe 2</option>
                <option value="3">Groupe 3</option>
                 <option value="4">Groupe 4</option>
            </select>
        <label for="salle">Salle:</label>
        <input type="text" id="salle" required>
        <label for="debut-cour">Début du cours:</label>
        <input type="time" id="debut-cour" required>
        <label for="fin-cour">Fin du cours:</label>
        <input type="time" id="fin-cour" required>
        <input type="submit" value="Confirmer">
        <button type="button" id="annuler">Annuler</button>
    </form>
</div>

    <script defer src="bib.js"></script>


</body>
</html>