<?php
namespace App\Interfaces\Service;

use App\Model\AccountModel; // Added use statement

interface IAccountService {
    /**
     * Retrieves all accounts.
     *
     * @return AccountModel[] An array of all accounts. Empty array if none.
     */
    public function getAllAccounts(): array; // PHP return type is kept as array

    /**
     * Retrieves a specific account by its ID.
     *
     * @param int $id The ID of the account.
     * @return AccountModel|null The account model, or null if not found.
     */
    public function getAccountById(int $id): ?AccountModel; // Updated return type

    /**
     * Creates a new account.
     *
     * @param array $data Associative array containing account data (e.g., ['name' => ..., 'email' => ...]).
     *                    Assumes data is already validated.
     * @return AccountModel|null The created account model, or null on failure.
     */
    public function createAccount(array $data): ?AccountModel; // Updated return type
}
?>
