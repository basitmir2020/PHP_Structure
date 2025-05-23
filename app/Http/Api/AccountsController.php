<?php
namespace App\Http\Api;

use App\Http\BaseApiController;
use App\Interfaces\Service\IAccountService; // Added
// use App\Persistence\DbContext; // Removed
use App\Util\Validator;

/**
 * AccountsController
 * 
 * Handles API requests for the 'accounts' resource.
 */
class AccountsController {
    private BaseApiController $api; // For sending responses
    private Validator $validator;   // For validating input
    private IAccountService $accountService; // For business logic and data access

    public function __construct(IAccountService $accountService) {
        $this->accountService = $accountService;
        $this->api = new BaseApiController();
        $this->validator = new Validator();
    }

    /**
     * Handles the incoming API request.
     * 
     * @param string $method HTTP request method (GET, POST, etc.)
     * @param string|null $id Optional resource ID from the URL
     * @param array $requestData Optional data from the request body (for POST, PUT)
     */
    public function handleRequest($method, $id = null, $requestData = []) {
        // Note: $this->api and $this->validator are now initialized in the constructor.
        // $db property is removed, and $this->accountService is used instead.

        switch (strtoupper($method)) {
            case 'GET':
                if ($id === null) {
                    // Get all accounts
                    $data = $this->accountService->getAllAccounts();
                    // getAllAccounts returns an array, empty if none.
                    $this->api->sendResponse($data);
                } else {
                    // Get a specific account by ID
                    if (!ctype_digit((string)$id) || (int)$id <= 0) { 
                        $this->api->sendError("Invalid account ID format", 400);
                        return;
                    }
                    $account = $this->accountService->getAccountById((int)$id);
                    if ($account !== null) {
                        $this->api->sendResponse($account); 
                    } else {
                        $this->api->sendError("Account not found", 404);
                    }
                }
                break;

            case 'POST':
                // Validator instance is already available as $this->validator
                $rules = [
                    'name' => ['required', 'minLength:2', 'maxLength:50'],
                    'email' => ['required', 'email', 'maxLength:100']
                ];

                if (!$this->validator->validate($requestData, $rules)) {
                    $this->api->sendError("Validation failed", 400, $this->validator->getErrors());
                    return; 
                }

                // Data for creation is $requestData, which has been validated.
                // The service layer expects an array with 'name' and 'email'.
                $newAccountId = $this->accountService->createAccount($requestData);

                if ($newAccountId !== null) {
                    $this->api->sendResponse(['message' => 'Account created successfully', 'id' => $newAccountId], 201);
                } else {
                    $this->api->sendError("Failed to create account", 500);
                }
                break;

            default:
                $this->api->sendError("Method not allowed", 405);
                break;
        }
    }
}
?>
