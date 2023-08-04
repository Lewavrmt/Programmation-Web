<?php

// fonction  de cette bibliotheque : 

//1- is_user_logged_in()
//2- redirect_user_to_welcome()
//3- authenticate_user($username, $password)
//4- get_user_role($username)
//5- is_user_allowed_to_edit($username)
//6- generateTable($date)




// verifie si l'utilisateur est connecte ou non

function is_user_logged_in() {
    return isset($_SESSION["username"]); // verifie si $_SESSION["username" a une valeur
}                                        // comme sa si elle existe sa veut dir que l'utilisateur c'ets bien connecter, quans il se connecte elle est cree  
                                        




function redirect_user_to_welcome() { // rediriger l'utilisateur vers la page de bienvenue lorsqu'il est connecte avec succes.
    header("Location: welcome.php"); // elle envoie un en-tete HTTP "Location" avec l'URL de la page de bienvenue, c'est-a-dire "welcome.php"
    exit;
}


function user_exists($username) {
    $users = json_decode(file_get_contents("users.json"), true);
    foreach ($users as $user) {
        if ($user["username"] == $username) {
            return true;
        }
    }
    return false;
}


//verifie si les identifiants (nom d'utilisateur et mot de passe) fournis correspondent a  un utilisateur existant dans le fichier "users.json"
//Si un utilisateur correspondant est trouve, la fonction retourne true et enregistre le nom d'utilisateur dans la variable de session $_SESSION["username"],
// sinon elle retourne false.

function authenticate_user($username, $password) {

    // Lecture du fichier "users.json" et décodage de son contenu JSON en un tableau associatif 
    $users = json_decode(file_get_contents("users.json"), true); 

    // Itération sur chaque utilisateur du tableau pour vérifier si le nom d'utilisateur correspond à celui fourni en paramètre.
    foreach ($users as $user) {
        if ($user["username"] == $username) {
            // Vérification du mot de passe en comparant le hachage stocké avec le mot de passe saisi
            if (password_verify($password, $user["password"])) {
                $_SESSION["username"] = $username;
                return true;
            }
        }
    }
    return false;
}




// recupere le role d'un utilisateur en fonction de son nom d'utilisateur
function get_user_role($username) {

    // Lecture du fichier "users.json" et decodage de son contenu JSON en un tableau associatif PHP.
    $users = json_decode(file_get_contents("users.json"), true); 

    // Itération sur chaque utilisateur du tableau pour vérifier si le nom d'utilisateur correspond à celui fourni en paramètre.
    foreach ($users as $user) {
        if ($user["username"] == $username) {
            return $user["role"]; // Si une correspondance est trouvee, la fonction retourne le role de cet utilisateur.
        }
    }
    return null;
}





// determine si un utilisateur a la permission de modifier certaines informations de l'application en fonction de son nom d'utilisateur.
function is_user_allowed_to_edit($username) {
    $user_role = get_user_role($username);
    return $user_role === "responsable" || $user_role === "coordinateur";
}





// genere une table de planning pour la semaine en cours, en fonction de la date donnee en argument. 
// Elle affiche le planning sous la forme d'un tableau HTML, avec les horaires et les jours de la semaine.

function generateTable($date) {
    ?>
        
        <!--afficher les jours de la semaine et les dates correspondantes dans les en-tetes de colonnes. --> 
        <table id="planning-table" data-date="<?= $date->format('Y-m-d') ?>">
            <tr>
                <th> </th>
                <th colspan="4"> Lundi <?= $date->format('m-d') ?> </th>
                <?php $date2 = clone $date; $date2->modify('+1 day'); ?>
                <th colspan="4"> Mardi <?= $date2->format('m-d') ?> </th>
                <?php $date3 = clone $date; $date3->modify('+2 day'); ?>
                <th colspan="4"> Mercredi <?= $date3->format('m-d') ?> </th>
                <?php $date4 = clone $date; $date4->modify('+3 day'); ?>
                <th colspan="4"> Jeudi <?= $date4->format('m-d') ?> </th>
                <?php $date5 = clone $date; $date5->modify('+4 day'); ?>
                <th colspan="4"> Vendredi <?= $date5->format('m-d') ?> </th>
            </tr>
            <tr>
                <th> </th>
                <?php for($i=0;$i<5;$i++){ ?> <!-- Cree une boucle for pour generer les en-tetes de colonnes pour les groupes (Gr1, Gr2, Gr3, Gr4) --> 
                    <th> Gr1 </th>
                    <th> Gr2 </th>
                    <th> Gr3 </th>
                    <th> Gr4 </th>
                <?php } ?>
            </tr>
            <!-- Ces variables seront utilisées pour afficher les horaires et les jours de la semaine dans le tableau. --> 
            <?php $heure = strtotime('07:45:00'); $date_col = clone $date; ?>
            <?php for($i=0;$i<44;$i++){ ?>
                <tr>
                    <td>
                        <!-- boucle for les lignes du tableau correspondant aux differentes heures de la journee, en incrementant l'heure par tranches de 15 minutes.-->
                        <?php $heure = strtotime('+15 minutes', $heure); ?>
                        <?php echo date('H:i', $heure); ?>
                    </td>
                    <?php for($j=0;$j<20;$j++){ ?>
                        <?php 
                            if($j % 4 == 0 && $j != 0){
                                $date_col->modify("+1 day");
                            }    
                        ?>
                        <td class="cell-<?= str_replace(':', 'h', date('H:i', $heure)); ?>-<?= $date_col->format('Y-m-d'); ?>-Gr<?= $j % 4 +1; ?>">
                            <button onclick="plus()" >+</button> <!-- ajoute egalement un bouton "+" dans chaque cellule -->
                        </td>
                    <?php } ?>
                </tr>
                <?php $date_col = clone $date; ?>
            <?php } ?>
        </table>
    <?php
    }
// Cette fonction genere donc un tableau de planning pour la semaine en cours, en fonction de la date donnee en argument.
// Elle permet d'afficher les creneaux horaires et les groupes pour chaque jour de la semaine, et fournit une structure pour 
//ajouter des evenements a ce planning.


?>