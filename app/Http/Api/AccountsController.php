<?php
namespace App\Http\Api;

use App\Http\BaseApiController;
use App\Persistence\DbContext;
use App\Util\Validator;

// Old require_once lines are removed as classes are autoloaded.
// require_once DBASE . "dbContext.tpl"; 
// require_once CONTROLLER . "apiController.tpl"; 

/**
 * AccountsController
 * 
 * Handles API requests for the 'accounts' resource.
 */
class AccountsController { // Renamed from accountsApiController

    /**
     * Handles the incoming API request.
     * 
     * @param string $method HTTP request method (GET, POST, etc.)
     * @param string|null $id Optional resource ID from the URL
     * @param array $requestData Optional data from the request body (for POST, PUT)
     */
    public function handleRequest($method, $id = null, $requestData = []) {
        $api = new BaseApiController(); // Updated instantiation
        $db = new DbContext();         // Updated instantiation

        switch (strtoupper($method)) {
            case 'GET':
                if ($id === null) {
                    $data = $db->select("SELECT id, name, email FROM accounts");
                    if ($data !== null) { 
                        $api->sendResponse($data);
                    } else {
                        $api->sendError("Error fetching accounts", 500);
                    }
                } else {
                    if (!ctype_digit((string)$id) || (int)$id <= 0) { 
                        $api->sendError("Invalid account ID format", 400);
                        return;
                    }
                    $data = $db->select("SELECT id, name, email FROM accounts WHERE id = ?", [$id]);
                    if (!empty($data)) {
                        $api->sendResponse($data[0]); 
                    } else {
                        $api->sendError("Account not found", 404);
                    }
                }
                break;

            case 'POST':
                $validator = new Validator(); // Updated instantiation
                $rules = [
                    'name' => ['required', 'minLength:2', 'maxLength:50'],
                    'email' => ['required', 'email', 'maxLength:100']
                ];

                if (!$validator->validate($requestData, $rules)) {
                    $api->sendError("Validation failed", 400, $validator->getErrors());
                    return; 
                }

                $name = $requestData['name']; 
                $email = $requestData['email'];

                $success = $db->insert("INSERT INTO accounts (name, email) VALUES (?, ?)", [$name, $email]);

                if ($success) {
                    $newId = $db->getConnection()->lastInsertId();
                    if ($newId) {
                        $api->sendResponse(['message' => 'Account created successfully', 'id' => $newId], 201);
                    } else {
                        $api->sendResponse(['message' => 'Account created successfully, ID retrieval issue'], 201);
                    }
                } else {
                    $api->sendError("Failed to create account", 500);
                }
                break;

            default:
                $api->sendError("Method not allowed", 405);
                break;
        }
    }
}
?>
