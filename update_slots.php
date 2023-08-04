<?php
// decode les deux tabmleau en format json 
// rajoute les slot à la fin du tableau 
// pour enregistrer il re encode sa en format json 

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["slot"])) {
    $slot = json_decode($_POST["slot"], true);

    $slots = json_decode(file_get_contents("slots.json"), true);
    $slots[] = $slot;

    file_put_contents("slots.json", json_encode($slots, JSON_PRETTY_PRINT));

    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}

?>