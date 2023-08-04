<?php
session_start();

require_once 'bib.php';

if (!is_user_logged_in() || !is_user_allowed_to_edit($_SESSION["username"])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Édition des listes - Calendrier collaboratif</title>
    <link rel="stylesheet" href="style.css">
    <script src="bib.js"></script>
</head>
<body>
    <header>
        <h1>Édition des listes - Calendrier collaboratif</h1>
    </header>
    <main>
        <div id="list-container">
            <section id="subjects-list">
                <h2>Matières</h2>
                <ul class="editable-list">
                    <!-- Les matières doivent être chargées dynamiquement avec JavaScript -->
                </ul>
                <button id="add-subject">Ajouter une matière</button>
            </section>
            
            <section id="teachers-list">
                <h2>Enseignants</h2>
                <ul class="editable-list">
                    <!-- Les enseignants doivent être chargés dynamiquement avec JavaScript -->
                </ul>
                <button id="add-teacher">Ajouter un enseignant</button>
            </section>
            
            <section id="rooms-list">
                <h2>Salles</h2>
                <ul class="editable-list">
                    <!-- Les salles doivent être chargées dynamiquement avec JavaScript -->
                </ul>
                <button id="add-room">Ajouter une salle</button>
            </section>
            
            <section id="groups-list">
                <h2>Groupes de TD</h2>
                <ul class="editable-list">
                    <!-- Les groupes de TD doivent être chargés dynamiquement avec JavaScript -->
                </ul>
                <button id="add-group">Ajouter un groupe de TD</button>
            </section>
        </div>
    </main>
</body>
</html>