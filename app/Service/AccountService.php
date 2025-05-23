<?php
namespace App\Service;

use App\Interfaces\Service\IAccountService;
use App\Persistence\DbContext;

class AccountService implements IAccountService {
    private $db;

    public function __construct(DbContext $db) {
        $this->db = $db;
    }

    /**
     * Retrieves all accounts.
     *
     * @return array An array of all accounts. Empty array if none.
     */
    public function getAllAccounts(): array {
        // Assuming DbContext::select returns null on error/no results, or an array of rows.
        $accounts = $this->db->select("SELECT id, name, email FROM accounts");
        return $accounts ?? []; // Ensure array return type
    }

    /**
     * Retrieves a specific account by its ID.
     *
     * @param int $id The ID of the account.
     * @return array|null The account data as an array, or null if not found.
     */
    public function getAccountById(int $id): ?array {
        // DbContext::select is expected to return an array of rows.
        // If a single row is found, it will be the first element of that array.
        $result = $this->db->select("SELECT id, name, email FROM accounts WHERE id = ?", [$id]);
        if (!empty($result)) {
            return $result[0]; // Return the first (and should be only) row
        }
        return null;
    }

    /**
     * Creates a new account.
     *
     * @param array $data Associative array containing account data (e.g., ['name' => ..., 'email' => ...]).
     *                    Assumes data is already validated by the controller/caller.
     * @return int|null The ID of the newly created account, or null on failure.
     */
    public function createAccount(array $data): ?int {
        // Ensure 'name' and 'email' keys exist in $data, though controller should validate this.
        // This is a service layer; primary validation should occur before calling this.
        if (!isset($data['name']) || !isset($data['email'])) {
            // Or throw an InvalidArgumentException
            error_log("AccountService::createAccount Error: Missing name or email in data array.");
            return null; 
        }

        $success = $this->db->insert(
            "INSERT INTO accounts (name, email) VALUES (?, ?)",
            [$data['name'], $data['email']]
        );

        if ($success) {
            // DbContext::getConnection() should return the PDO connection
            $lastId = $this->db->getConnection()->lastInsertId();
            return $lastId ? (int)$lastId : null; // Ensure it's an int or null
        }
        return null;
    }
}
?>
