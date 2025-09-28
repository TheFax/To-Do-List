<?php
session_start();
include 'utilities.php';

header('Content-Type: application/json');

if ($_POST["title"] != "" && $_POST["category"] != "") {
    $title = filter($_POST["title"]);
    $description = filter($_POST["description"]);
    $category = filter($_POST["category"]);

    if ($title != "" && $category != "") {
        //Aggiungo i dati in database
        include 'db_class.php';
        $my_db = new sql_class;
        $my_db->addItem($title, $description, $category);
        echo json_encode([
            "status" => "success",
            "message" => "Task aggiunto con successo."
        ]);
        exit;
    } else {
        echo json_encode([
            "status" => "danger",
            "message" => "Inserimento non eseguito: non puoi usare caratteri speciali."
        ]);
        exit;
    }
} else {
    echo json_encode([
        "status" => "danger",
        "message" => "Inserimento non eseguito: servono titolo e categoria."
    ]);
    exit;
}
