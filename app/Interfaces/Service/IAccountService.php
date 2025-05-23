<?php
namespace App\Interfaces\Service;

interface IAccountService {
    /**
     * Retrieves all accounts.
     *
     * @return array An array of all accounts. Empty array if none.
     */
    public function getAllAccounts(): array;

    /**
     * Retrieves a specific account by its ID.
     *
     * @param int $id The ID of the account.
     * @return array|null The account data as an array, or null if not found.
     */
    public function getAccountById(int $id): ?array;

    /**
     * Creates a new account.
     *
     * @param array $data Associative array containing account data (e.g., ['name' => ..., 'email' => ...]).
     *                    Assumes data is already validated.
     * @return int|null The ID of the newly created account, or null on failure.
     */
    public function createAccount(array $data): ?int;
}
?>
