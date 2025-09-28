<?php
session_start();
include 'utilities.php';

header('Content-Type: application/json');

if (isset($_POST["id"]) && $_POST["id"] != "") {
    $id = filter($_POST["id"]);
    if (is_numeric($id)) {
        include 'db_class.php';
        $my_db = new sql_class;
        $my_db->deleteItem($id);
        echo json_encode([
            "status" => "success",
            "message" => "Nota eliminata con successo."
        ]);
        exit;
    } else {
        echo json_encode([
            "status" => "danger",
            "message" => "ID non valido."
        ]);
        exit;
    }
} else {
    echo json_encode([
        "status" => "danger",
        "message" => "Nota non eliminata: non è stato specificato l'ID."
    ]);
    exit;
}
