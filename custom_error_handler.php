<?php
// error_handler.php

// Abilita la visualizzazione degli errori solo durante lo sviluppo
// In produzione, impostare a 'Off' o disabilitare completamente per sicurezza
ini_set('display_errors', 'Off'); // Non mostrare errori sul browser
ini_set('log_errors', 'On');      // Assicurati che gli errori vengano loggati
ini_set('error_log', __DIR__ . DIRECTORY_SEPARATOR . 'php_errors.log'); // Specifica un file di log

/**
 * Funzione personalizzata per la gestione degli errori e delle eccezioni.
 * Questa funzione intercetta gli errori e le eccezioni non catturate,
 * registrandoli tramite error_log e prevenendo la visualizzazione di
 * dettagli sensibili all'utente.
 *
 * @param Throwable $exception L'eccezione o l'errore catturato.
 */
function customErrorHandler(Throwable $exception) {
    $errorMessage = sprintf(
        "[%s] Errore non gestito/Eccezione: %s in %s alla linea %d. Stack Trace: %s",
        date('Y-m-d H:i:s'),
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );

    // Registra l'errore nel file di log specificato da php.ini o da ini_set
    error_log($errorMessage);

    // In un ambiente di produzione, mostra un messaggio generico all'utente
    // e termina l'esecuzione.
    if (ini_get('display_errors') === 'Off') {
        http_response_code(500); // Imposta lo stato HTTP a 500 Internal Server Error
        echo "Si è verificato un errore inaspettato. Riprova più tardi o contatta l'amministratore.";
    } else {
        // Durante lo sviluppo, puoi mostrare più dettagli
        http_response_code(500);
        echo "<h1>Errore Critico</h1>";
        echo "<p>Si è verificato un errore inaspettato:</p>";
        echo "<pre>" . htmlspecialchars($errorMessage) . "</pre>";
    }

    exit(); // Termina l'esecuzione dello script dopo un errore critico
}

// Registra la funzione come gestore predefinito delle eccezioni
set_exception_handler('customErrorHandler');

// Puoi anche registrare un gestore per gli errori PHP tradizionali (warning, notice, etc.)
// set_error_handler('customErrorHandler'); // Non sempre necessario se le eccezioni sono gestite bene