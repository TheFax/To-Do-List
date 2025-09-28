<?php
require_once 'custom_error_handler.php';

class sql_class
{
    /*
      Link per imparare:
      Tipi di dati SQL:   https://www.w3schools.com/sql/sql_datatypes.asp
      Basi di SQL:        https://www.codecademy.com/articles/sql-commands
      Sicurezza PDO:      https://phpdelusions.net/pdo#security
      Logging degli errori: https://www.php.net/manual/en/function.error-log.php
    */

    private PDO $pdo;

    const DB_FILE = 'db.sqlite';
    const TABLE_NAME = 'tabella';
    const DEFAULT_TIMEZONE = 'Europe/Rome';
    const DEFAULT_TASK_STATUS = 'Active'; // Stato predefinito delle attività

    /**
     * Costruttore della classe sql_class.
     * Stabilisce la connessione PDO e crea la tabella se non esiste.
     * @throws Exception Se la connessione al database o la creazione della tabella falliscono.
     */
    public function __construct()
    {
        try {
            // Crea la connessione al database
            // Percorso assoluto per il file del database per evitare problemi con la directory corrente
            $dbPath = __DIR__ . DIRECTORY_SEPARATOR . self::DB_FILE;

            $this->pdo = new PDO('sqlite:' . $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Cruciale per prevenire SQL Injection
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Imposta il fetch mode predefinito

            // Imposta il fuso orario predefinito per una gestione coerente di data/ora
            date_default_timezone_set(self::DEFAULT_TIMEZONE);

            // Crea la tabella, se non esiste
            // Aggiunto NOT NULL e DEFAULT per task_status per consistenza dei dati
            $this->pdo->exec('CREATE TABLE IF NOT EXISTS ' . self::TABLE_NAME . ' (
                                  id                INTEGER PRIMARY KEY AUTOINCREMENT,
                                  position          INTEGER DEFAULT 0,
                                  task_category     TEXT NOT NULL,
                                  task_title        TEXT NOT NULL,
                                  task_description  TEXT,
                                  add_date          TEXT,
                                  delete_date       TEXT,
                                  task_status       TEXT NOT NULL DEFAULT "' . self::DEFAULT_TASK_STATUS . '"
                              );');
        } catch (PDOException $e) {
            // Log dell'errore completo per il debug (non mostrare all'utente)
            error_log("ERRORE CRITICO DATABASE: Connessione o creazione tabella fallita - " . $e->getMessage() . " in " . $e->getFile() . " alla linea " . $e->getLine());
            // Lancia un'eccezione generica per l'utente/applicazione
            throw new Exception("Impossibile connettersi al database o creare la tabella. Riprovare più tardi.");
        }
    }

    /**
     * Legge tutte le attività attive dal database, ordinate per categoria e posizione.
     * @return array Un array di array associativi che rappresentano le attività.
     * @throws Exception Se si verifica un errore del database durante l'operazione di lettura.
     */
    public function readAll(): array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM ' . self::TABLE_NAME . ' WHERE task_status = :task_status ORDER BY task_category ASC, position ASC');
            $stmt->bindValue(':task_status', self::DEFAULT_TASK_STATUS, PDO::PARAM_STR); // Usa la costante
            $stmt->execute();
            return $stmt->fetchAll(); // Il fetch mode è già impostato nel costruttore
        } catch (PDOException $e) {
            error_log("Errore DB in readAll: " . $e->getMessage());
            throw new Exception("Impossibile recuperare tutte le attività attive.");
        }
    }

    /**
     * Legge una singola attività dal database tramite il suo ID.
     * @param int $id L'ID dell'attività da recuperare.
     * @return array Un array associativo che rappresenta l'attività, o un array vuoto se non trovata.
     * @throws InvalidArgumentException Se l'ID fornito non è valido.
     * @throws Exception Se si verifica un errore del database.
     */
    public function readID(int $id): array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID non valido: l'ID deve essere un intero positivo.");
        }

        try {
            $stmt = $this->pdo->prepare('SELECT * FROM ' . self::TABLE_NAME . ' WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(); // Il fetch mode è già impostato
            return $result ?: []; // Restituisce un array vuoto se non trovato
        } catch (PDOException $e) {
            error_log("Errore DB in readID per ID " . $id . ": " . $e->getMessage());
            throw new Exception("Impossibile recuperare l'attività con ID " . $id . ".");
        }
    }

    /**
     * Aggiunge una nuova attività al database.
     * @param string $title Il titolo dell'attività.
     * @param string $description La descrizione dell'attività.
     * @param string $category La categoria dell'attività.
     * @return int L'ID dell'attività appena inserita.
     * @throws InvalidArgumentException Se un parametro di input è vuoto.
     * @throws Exception Se si verifica un errore del database.
     */
    public function addItem(string $title, string $description, string $category): int
    {
        // Trimma gli input per rimuovere spazi bianchi non necessari
        $title = trim($title);
        $description = trim($description);
        $category = trim($category);

        if (empty($title) || empty($category)) {
            throw new InvalidArgumentException("Titolo e categoria non possono essere vuoti.");
        }

        try {
            $datetime = date("Y-m-d H:i:s");
            $stmt = $this->pdo->prepare('INSERT INTO ' . self::TABLE_NAME . ' (task_category, task_title, task_description, add_date, task_status) VALUES (:task_category, :task_title, :task_description, :add_date, :task_status)');

            $stmt->bindValue(':task_category', $category, PDO::PARAM_STR);
            $stmt->bindValue(':task_title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':task_description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':add_date', $datetime, PDO::PARAM_STR);
            $stmt->bindValue(':task_status', self::DEFAULT_TASK_STATUS, PDO::PARAM_STR);
            $stmt->execute();

            return (int) $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Errore DB in addItem: " . $e->getMessage());
            throw new Exception("Impossibile aggiungere una nuova attività.");
        }
    }

    /**
     * Aggiorna le posizioni di più attività in un'unica transazione.
     * @param array $updates Un array associativo dove le chiavi sono gli ID delle attività e i valori sono le nuove posizioni.
     * @return bool Vero in caso di successo, falso in caso di fallimento.
     * @throws InvalidArgumentException Se l'array degli aggiornamenti non è valido.
     * @throws Exception Se si verifica un errore del database durante la transazione.
     */
    public function updatePositions(array $updates): bool
    {
        if (empty($updates)) {
            return true; // Nessun aggiornamento da processare
        }

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare('UPDATE ' . self::TABLE_NAME . ' SET position = :position WHERE id = :id');
            foreach ($updates as $id => $position) {
                // Filtra e valida gli input per ID e posizione
                $id = filter_var($id, FILTER_VALIDATE_INT);
                $position = filter_var($position, FILTER_VALIDATE_INT);

                if ($id === false || $id <= 0 || $position === false || $position < 0) {
                    $this->pdo->rollBack();
                    throw new InvalidArgumentException("ID o posizione non validi per l'aggiornamento: ID " . $id . ", Posizione " . $position);
                }
                $stmt->bindValue(':position', $position, PDO::PARAM_INT);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Errore DB durante updatePositions: " . $e->getMessage());
            throw new Exception("Impossibile aggiornare le posizioni delle attività.");
        }
    }

    /**
     * Contrassegna un'attività come "Completata" e imposta la data di eliminazione e la posizione a -2.
     * @param int $id L'ID dell'attività da contrassegnare come completata.
     * @return bool Vero in caso di successo, falso in caso di fallimento.
     * @throws InvalidArgumentException Se l'ID fornito non è valido.
     * @throws Exception Se si verifica un errore del database.
     */
    public function doneItem(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID non valido per doneItem: l'ID deve essere un intero positivo.");
        }

        try {
            $datetime = date("Y-m-d H:i:s");
            $stmt = $this->pdo->prepare('UPDATE ' . self::TABLE_NAME . ' SET task_status = :task_status, delete_date = :delete_date, position = :position WHERE id = :id');

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':task_status', "Done", PDO::PARAM_STR);
            $stmt->bindValue(':delete_date', $datetime, PDO::PARAM_STR);
            $stmt->bindValue(':position', -2, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Errore DB in doneItem per ID " . $id . ": " . $e->getMessage());
            throw new Exception("Impossibile contrassegnare l'attività con ID " . $id . " come completata.");
        }
    }

    /**
     * Elimina un'attività dal database tramite il suo ID.
     * @param int $id L'ID dell'attività da eliminare.
     * @return bool Vero in caso di successo, falso in caso di fallimento.
     * @throws InvalidArgumentException Se l'ID fornito non è valido.
     * @throws Exception Se si verifica un errore del database.
     */
    public function deleteItem(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID non valido per deleteItem: l'ID deve essere un intero positivo.");
        }

        try {
            $stmt = $this->pdo->prepare('DELETE FROM ' . self::TABLE_NAME . ' WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Errore DB in deleteItem per ID " . $id . ": " . $e->getMessage());
            throw new Exception("Impossibile eliminare l'attività con ID " . $id . ".");
        }
    }

    /**
     * Rimuove tutte le attività dal database ed esegue il VACUUM sul file del database.
     * @return bool Vero in caso di successo.
     * @throws Exception Se si verifica un errore del database.
     */
    public function removeAll(): bool
    {
        $this->pdo->beginTransaction(); // Assicuriamo l'atomicita' anche qui
        try {
            $this->pdo->exec('DELETE FROM ' . self::TABLE_NAME . ';'); // Prima la DELETE
            $this->pdo->exec('VACUUM;'); // Poi la VACUUM
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Errore DB in removeAll: " . $e->getMessage());
            throw new Exception("Impossibile rimuovere tutte le attività e ottimizzare il database.");
        }
    }

    /**
     * Modifica un'attività esistente nel database.
     * @param int $id L'ID dell'attività da modificare.
     * @param string $title Il nuovo titolo dell'attività.
     * @param string $description La nuova descrizione dell'attività.
     * @param string $category La nuova categoria dell'attività.
     * @return bool Vero in caso di successo, falso in caso di fallimento.
     * @throws InvalidArgumentException Se l'ID fornito o un parametro stringa non è valido.
     * @throws Exception Se si verifica un errore del database.
     */
    public function editItem(int $id, string $title, string $description, string $category): bool
    {
        // Trimma gli input
        $title = trim($title);
        $description = trim($description);
        $category = trim($category);

        if ($id <= 0) {
            throw new InvalidArgumentException("ID non valido per editItem: l'ID deve essere un intero positivo.");
        }
        if (empty($title) || empty($category)) {
            throw new InvalidArgumentException("Titolo e categoria non possono essere vuoti per editItem.");
        }

        try {
            $stmt = $this->pdo->prepare('UPDATE ' . self::TABLE_NAME . ' SET task_category = :task_category, task_title = :task_title, task_description = :task_description WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':task_category', $category, PDO::PARAM_STR);
            $stmt->bindValue(':task_title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':task_description', $description, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Errore DB in editItem per ID " . $id . ": " . $e->getMessage());
            throw new Exception("Impossibile modificare l'attività con ID " . $id . ".");
        }
    }
}