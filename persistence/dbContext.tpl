<?php
class dbContext {
    private $con = null; // Initialize to null

    /**
     * Constructor
     * 
     * Initializes the database connection.
     * Logs an error if the connection fails.
     */
    function __construct() {
        $this->con = self::connect();
        if (!$this->con) {
            // Log error or handle connection failure
            error_log("dbContext: CRITICAL - Failed to connect to the database in constructor.");
        }
    }

    /**
     * Connect to the database using PDO.
     * 
     * @return PDO|false PDO connection object or false on failure.
     */
    private static function connect() {
        // DBHOST, DBNAME, DBUSER, DBPASS are expected to be defined (e.g., in config.tpl)
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Important for error handling
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   // Default fetch mode
            PDO::ATTR_EMULATE_PREPARES   => false, // Use true prepared statements
        ];

        try {
            return new PDO($dsn, DBUSER, DBPASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the PDO connection object.
     * 
     * @return PDO|false The PDO connection object or false if not connected.
     */
    public function getConnection() {
        return $this->con;
    }

    /**
     * Execute a SELECT query.
     * 
     * @param string $sql SQL query with placeholders.
     * @param array $params Parameters to bind to the query.
     * @return array Array of results, or empty array on failure or if not connected.
     */
    public function select($sql, $params = []) {
        if (!$this->con) {
            error_log("dbContext->select: No database connection.");
            return []; // Return empty array as per prompt preference over null
        }
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(); // PDO::FETCH_ASSOC is default
        } catch (PDOException $e) {
            error_log("dbContext->select Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return []; // Return empty array on error
        }
    }

    /**
     * Execute an INSERT query.
     * 
     * @param string $sql SQL query with placeholders.
     * @param array $params Parameters to bind to the query.
     * @return bool True on success, false on failure or if not connected.
     */
    public function insert($sql, $params = []) {
        if (!$this->con) {
            error_log("dbContext->insert: No database connection.");
            return false;
        }
        try {
            $stmt = $this->con->prepare($sql);
            return $stmt->execute($params); // execute() returns true on success, false on failure
        } catch (PDOException $e) {
            error_log("dbContext->insert Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return false;
        }
    }

    /**
     * Execute an UPDATE query.
     * 
     * @param string $sql SQL query with placeholders.
     * @param array $params Parameters to bind to the query.
     * @return bool True if rows were affected, false otherwise or on error or if not connected.
     */
    public function update($sql, $params = []) {
        if (!$this->con) {
            error_log("dbContext->update: No database connection.");
            return false;
        }
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("dbContext->update Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return false;
        }
    }

    /**
     * Execute a DELETE query.
     * 
     * @param string $sql SQL query with placeholders.
     * @param array $params Parameters to bind to the query.
     * @return bool True if rows were affected, false otherwise or on error or if not connected.
     */
    public function delete($sql, $params = []) {
        if (!$this->con) {
            error_log("dbContext->delete: No database connection.");
            return false;
        }
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("dbContext->delete Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return false;
        }
    }
}
?>