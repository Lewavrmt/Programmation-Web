<?php


session_start();

require_once 'bib.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (user_exists($username)) {
        $error_message = "L'identifiant existe déjà. Veuillez en choisir un autre.";
    } else {
        // Ajoutez le nouvel utilisateur au fichier JSON
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $new_user = [
            "username" => $username,
            "password" => $hashed_password,
            "role" => $role // Utilisez le rôle sélectionné dans le formulaire.
        ];

        $users = json_decode(file_get_contents("users.json"), true);
        $users[] = $new_user;

        // Convertir le tableau en JSON avec l'option JSON_PRETTY_PRINT
        $json_data = json_encode($users, JSON_PRETTY_PRINT);

        // Écrire le JSON formaté dans le fichier
        file_put_contents("users.json", $json_data);

        // Connectez automatiquement l'utilisateur après l'inscription.
        $_SESSION["username"] = $username;
        redirect_user_to_welcome();
    }
}
?>

<!-- Affichez le formulaire d'inscription avec les erreurs potentielles -->

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Calendrier collaboratif</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> 
    <header>
        <h1>Inscription</h1>
    </header>
    <main>
        <h2>Inscription</h2>
        <form action="inscription.php" method="post">
            <div>
                <label for="username">Identifiant :</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="role">Rôle :</label>
                <select id="role" name="role" required>
                    <option value="etudiant">Étudiant</option>
                    <option value="responsable">Responsable</option>
                    <option value="coordinateur">Coordinateur</option>
                </select>
            </div>

            <?php if ($error_message): ?>
                <p class="error-message"><?= $error_message ?></p>
            <?php endif; ?>
            <input type="submit" value="S'inscrire">
        </form>

        <hr>

        

    </main>
</body>
</html>
