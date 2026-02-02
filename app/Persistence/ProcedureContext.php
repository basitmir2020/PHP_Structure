<?php
namespace App\Persistence;

use PDO;
use PDOException;

class ProcedureContext
{
    private $pdo;

    /**
     * ProcedureContext constructor.
     * 
     * @param PDO $pdo The active PDO connection instance.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Call a stored procedure.
     * 
     * Efficiently executes a stored procedure using prepared statements.
     * Supports returning result sets (fetchAll).
     *
     * @param string $procedureName The name of the stored procedure.
     * @param array $params Optional parameters to pass to the procedure.
     * @return array|bool Returns an associative array of results for SELECT procedures, or true/false for actions.
     */
    public function call(string $procedureName, array $params = [])
    {
        try {
            // Build the placeholder string (e.g., "?, ?, ?")
            $placeholders = $this->buildPlaceholders($params);

            // Prepare the CALL statement
            $sql = "CALL {$procedureName}({$placeholders})";
            $stmt = $this->pdo->prepare($sql);

            // Execute with parameters
            $success = $stmt->execute($params);

            // Check if there are results to fetch
            if ($success && $stmt->columnCount() > 0) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $success;

        } catch (PDOException $e) {
            error_log("ProcedureContext->call Error: " . $e->getMessage() . " | Proc: " . $procedureName . " | Params: " . json_encode($params));
            return false;
        }
    }

    /**
     * Execute a stored SQL function.
     * 
     * Calls a function via "SELECT function_name(?, ?)" and returns the scalar result.
     *
     * @param string $functionName The name of the SQL function.
     * @param array $params Optional parameters.
     * @return mixed The return value of the function, or false on failure.
     */
    public function executeFunction(string $functionName, array $params = [])
    {
        try {
            $placeholders = $this->buildPlaceholders($params);

            // Prepare SELECT function_name(...)
            // We alias it to 'result' to easily fetch it
            $sql = "SELECT {$functionName}({$placeholders}) AS result";
            $stmt = $this->pdo->prepare($sql);

            $stmt->execute($params);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['result'] : null;

        } catch (PDOException $e) {
            error_log("ProcedureContext->executeFunction Error: " . $e->getMessage() . " | Func: " . $functionName . " | Params: " . json_encode($params));
            return false;
        }
    }

    /**
     * Helper to build comma-separated placeholders.
     */
    private function buildPlaceholders(array $params): string
    {
        return empty($params) ? '' : implode(', ', array_fill(0, count($params), '?'));
    }
}
