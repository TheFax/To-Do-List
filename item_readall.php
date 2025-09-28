<?php
require_once "db_class.php";

header('Content-Type: application/json'); // Set header to indicate JSON response

$my_db = new sql_class;
$items_list = $my_db->readAll();

echo json_encode($items_list); // Encode the array into a JSON string and output it
