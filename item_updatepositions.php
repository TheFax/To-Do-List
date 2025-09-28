<?php
header('Content-Type: application/json'); // Fondamentale per indicare che la risposta è JSON
session_start();
require_once 'db_class.php';

$response = ['status' => 'error', 'message' => 'Richiesta iniziale non valida.'];

// Verifica che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Legge il body RAW della richiesta POST, che contiene il JSON inviato dal client
    $input = file_get_contents('php://input');
    $data = json_decode($input, true); // Decodifica il JSON in un array associativo

    // Verifica che la decodifica JSON sia andata a buon fine e che l'array 'item_ids' esista
    if (json_last_error() === JSON_ERROR_NONE && isset($data['item_ids']) && is_array($data['item_ids'])) {
        $item_ids = $data['item_ids'];

        // Crea un'istanza della tua classe SQL
        try {
            $db = new sql_class();
        } catch (PDOException $e) {
            // Errore nella connessione al DB (es. file sqlite non accessibile)
            $response = ['status' => 'error', 'message' => 'Errore di connessione al database: ' . $e->getMessage()];
            echo json_encode($response);
            exit(); // Termina lo script immediatamente
        }

        // Prepara l'array di aggiornamenti per la funzione updatePositions:
        // La funzione updatePositions si aspetta un array associativo [id => position]
        $updates = [];
        foreach ($item_ids as $position => $id) {
            // Converto l'ID in intero per sicurezza
            $updates[(int)$id] = (int)$position;
        }

        try {
            // Chiama il metodo updatePositions della classe per aggiornare le posizioni nel DB
            $db->updatePositions($updates);
            $response = ['status' => 'success', 'message' => 'Posizioni aggiornate con successo.'];
        } catch (Exception $e) {
            // Cattura eventuali eccezioni lanciate dal metodo updatePositions (es. errori SQL)
            $response = ['status' => 'error', 'message' => 'Errore interno del server durante l\'aggiornamento delle posizioni: ' . $e->getMessage()];
        }
    } else {
        // La decodifica JSON è fallita o i dati non sono nel formato atteso
        $response = ['status' => 'error', 'message' => 'Dati JSON ricevuti non validi o formato inatteso. JSON Error: ' . json_last_error_msg()];
    }
}

// Invia la risposta JSON al client
echo json_encode($response);
