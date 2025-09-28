<?php
session_start();
include 'utilities.php';

if ($_GET["id"] != "") {
    $id = filter($_GET["id"]);

    if (is_numeric($id)) {
        include 'db_class.php';
        $my_db = new sql_class;
        $my_db->doneItem($id);
    }
} else {

    $_SESSION["message"] = "Nota non marcata come DONE: non è stato specificato l'ID.";
    $_SESSION["status"] = "danger";
}

header('location: index.php');
