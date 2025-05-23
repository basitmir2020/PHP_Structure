<?php
namespace App\Service;

use App\Interfaces\Service\IAccountService;
use App\Persistence\DbContext;
use App\Model\AccountModel; // Added use statement

class AccountService implements IAccountService {
    private $db;

    public function __construct(DbContext $db) {
        $this->db = $db;
    }

    /**
     * Retrieves all accounts.
     *
     * @return AccountModel[] An array of AccountModel objects. Empty array if none.
     */
    public function getAllAccounts(): array {
        $accountsData = $this->db->select("SELECT id, name, email FROM accounts");
        $accountModels = [];
        if ($accountsData) {
            foreach ($accountsData as $accData) {
                $accountModels[] = new AccountModel($accData['name'], $accData['email'], (int)$accData['id']);
            }
        }
        return $accountModels;
    }

    /**
     * Retrieves a specific account by its ID.
     *
     * @param int $id The ID of the account.
     * @return AccountModel|null The account model, or null if not found.
     */
    public function getAccountById(int $id): ?AccountModel {
        $result = $this->db->select("SELECT id, name, email FROM accounts WHERE id = ?", [$id]);
        if (!empty($result)) {
            $accData = $result[0];
            return new AccountModel($accData['name'], $accData['email'], (int)$accData['id']);
        }
        return null;
    }

    /**
     * Creates a new account.
     *
     * @param array $data Associative array containing account data (e.g., ['name' => ..., 'email' => ...]).
     *                    Assumes data is already validated by the controller/caller.
     * @return AccountModel|null The created account model, or null on failure.
     */
    public function createAccount(array $data): ?AccountModel {
        if (!isset($data['name']) || !isset($data['email'])) {
            error_log("AccountService::createAccount Error: Missing name or email in data array.");
            return null; 
        }

        $success = $this->db->insert(
            "INSERT INTO accounts (name, email) VALUES (?, ?)",
            [$data['name'], $data['email']]
        );

        if ($success) {
            $lastId = $this->db->getConnection()->lastInsertId();
            if ($lastId) {
                return new AccountModel($data['name'], $data['email'], (int)$lastId);
            }
        }
        return null;
    }
}
?>
