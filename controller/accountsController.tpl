<?php
// Ensure config.tpl is loaded by index.php or similar, providing DBASE and CONTROLLER constants.
require_once DBASE . "dbContext.tpl";
require_once CONTROLLER . "apiController.tpl";

/**
 * accountsApiController
 * 
 * Handles API requests for the 'accounts' resource.
 */
class accountsApiController {

    /**
     * Handles the incoming API request.
     * 
     * @param string $method HTTP request method (GET, POST, etc.)
     * @param string|null $id Optional resource ID from the URL
     * @param array $requestData Optional data from the request body (for POST, PUT)
     */
    public function handleRequest($method, $id = null, $requestData = []) {
        $api = new ApiController();
        $db = new dbContext();

        switch (strtoupper($method)) {
            case 'GET':
                if ($id === null) {
                    // Get all accounts
                    $data = $db->select("SELECT id, name, email FROM accounts");
                    // dbContext->select now returns an array, or an empty array on error/no results.
                    // It logs errors internally.
                    if ($data !== null) { // Check if $data is not null (though it should be [] on error from dbContext)
                        $api->sendResponse($data);
                    } else {
                        // This case might be redundant if dbContext always returns [] on error.
                        $api->sendError("Error fetching accounts", 500);
                    }
                } else {
                    // Get a specific account by ID
                    // ID validation (e.g. numeric) can still be useful.
                    if (!ctype_digit((string)$id) || (int)$id <= 0) { // Basic validation for positive integer ID
                        $api->sendError("Invalid account ID format", 400);
                        return;
                    }

                    $data = $db->select("SELECT id, name, email FROM accounts WHERE id = ?", [$id]);

                    if (!empty($data)) {
                        // $data is an array of rows, for specific ID, expect 0 or 1 row.
                        $api->sendResponse($data[0]); 
                    } else {
                        $api->sendError("Account not found", 404);
                    }
                }
                break;

            case 'POST':
                // Validator is expected to be included via config.tpl
                $validator = new Validator();
                $rules = [
                    'name' => ['required', 'minLength:2', 'maxLength:50'],
                    'email' => ['required', 'email', 'maxLength:100']
                ];

                // Assuming $requestData holds the JSON payload or POST data
                if (!$validator->validate($requestData, $rules)) {
                    $api->sendError("Validation failed", 400, $validator->getErrors());
                    return; // Important to stop further execution
                }

                // If validation passes, proceed with data extraction and database logic
                // Note: $requestData contains the raw data. It's good practice to use the validated fields.
                // The Validator class doesn't sanitize, only validates.
                // For this use case, $requestData['name'] and $requestData['email'] are used directly.
                $name = $requestData['name']; 
                $email = $requestData['email'];

                // Name and email are used directly in prepared statement parameters
                $success = $db->insert("INSERT INTO accounts (name, email) VALUES (?, ?)", [$name, $email]);

                if ($success) {
                    $newId = $db->getConnection()->lastInsertId();
                    if ($newId) {
                        $api->sendResponse(['message' => 'Account created successfully', 'id' => $newId], 201);
                    } else {
                        $api->sendResponse(['message' => 'Account created successfully, ID retrieval issue'], 201);
                    }
                } else {
                    // dbContext logs the detailed error internally
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
