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
                    $sql = "SELECT id, name, email FROM accounts";
                    $result = $db->select($sql);

                    if ($result) {
                        $data = [];
                        // Standard way to fetch all rows if using mysqli
                        if (method_exists($result, 'fetch_all')) { // mysqli_result
                            $data = $result->fetch_all(MYSQLI_ASSOC);
                        } else { // PDOStatement or other
                            while ($row = $result->fetch_assoc()) { // Assuming fetch_assoc for mysqli
                                $data[] = $row;
                            }
                        }
                        $api->sendResponse($data);
                    } else {
                        $api->sendError("Error fetching accounts: " . $db->con->error, 500); // Added DB error for diagnostics
                    }
                } else {
                    // Get a specific account by ID
                    // Basic sanitization for ID (in a real app, use prepared statements)
                    $sanitizedId = filter_var($id, FILTER_SANITIZE_NUMBER_INT); 
                    if (!$sanitizedId) {
                        $api->sendError("Invalid account ID format", 400);
                        return;
                    }
                    
                    $sql = "SELECT id, name, email FROM accounts WHERE id = '$sanitizedId'";
                    $result = $db->select($sql);

                    if ($result) {
                        if (method_exists($result, 'fetch_assoc')) { // mysqli_result
                             $data = $result->fetch_assoc();
                        } else { // Should not happen if select() consistent
                            $api->sendError("Error fetching account data structure", 500);
                            return;
                        }

                        if ($data) {
                            $api->sendResponse($data);
                        } else {
                            $api->sendError("Account not found", 404);
                        }
                    } else {
                        $api->sendError("Error fetching account: " . $db->con->error, 500); // Added DB error
                    }
                }
                break;

            case 'POST':
                $name = $requestData['name'] ?? null;
                $email = $requestData['email'] ?? null;

                if ($name && $email) {
                    // Basic sanitization (in a real app, use prepared statements and more robust validation)
                    $sanitizedName = filter_var($name, FILTER_SANITIZE_STRING);
                    $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

                    if (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
                        $api->sendError("Invalid email format", 400, ['email' => 'Invalid email format']);
                        return;
                    }

                    // In a real application, you would use mysqli_real_escape_string or prepared statements
                    // For this example, assuming basic filtering is illustrative.
                    $sql = "INSERT INTO accounts (name, email) VALUES ('$sanitizedName', '$sanitizedEmail')";
                    $success = $db->insert($sql);

                    if ($success) {
                        // $db->con is private. Cannot get insert_id directly.
                        // Consider adding a method to dbContext to get last insert ID.
                        // For now, sending a generic success message.
                        $api->sendResponse(['message' => 'Account created successfully'], 201);
                    } else {
                        $api->sendError("Failed to create account: " . $db->con->error, 500); // Added DB error
                    }
                } else {
                    $errors = [];
                    if (!$name) $errors['name'] = 'Name is required';
                    if (!$email) $errors['email'] = 'Email is required';
                    $api->sendError("Missing required fields for creating account", 400, $errors);
                }
                break;

            default:
                $api->sendError("Method not allowed", 405);
                break;
        }
    }
}
?>
