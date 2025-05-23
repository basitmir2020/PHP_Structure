<?php
namespace App\Persistence;

use PDO;
use PDOException;

class DbContext { // Renamed from dbContext to DbContext for PSR-1 compliance
    private $con = null; 

    function __construct() {
        $this->con = self::connect();
        if (!$this->con) {
            error_log("DbContext: CRITICAL - Failed to connect to the database in constructor.");
        }
    }

    private static function connect() {
        $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,   
            PDO::ATTR_EMULATE_PREPARES   => false, 
        ];

        try {
            return new PDO($dsn, DBUSER, DBPASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            return false;
        }
    }

    public function getConnection() {
        return $this->con;
    }

    public function select($sql, $params = []) {
        if (!$this->con) {
            error_log("DbContext->select: No database connection.");
            return []; 
        }
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(); 
        } catch (PDOException $e) {
            error_log("DbContext->select Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return []; 
        }
    }

    public function insert($sql, $params = []) {
        if (!$this->con) {
            error_log("DbContext->insert: No database connection.");
            return false;
        }
        try {
            $stmt = $this->con->prepare($sql);
            return $stmt->execute($params); 
        } catch (PDOException $e) {
            error_log("DbContext->insert Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return false;
        }
    }

    public function update($sql, $params = []) {
        if (!$this->con) {
            error_log("DbContext->update: No database connection.");
            return false;
        }
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("DbContext->update Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return false;
        }
    }

    public function delete($sql, $params = []) {
        if (!$this->con) {
            error_log("DbContext->delete: No database connection.");
            return false;
        }
        try {
            $stmt = $this->con->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("DbContext->delete Error: " . $e->getMessage() . " | SQL: " . $sql . " | Params: " . json_encode($params));
            return false;
        }
    }
}
?>
