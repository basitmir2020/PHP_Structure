<?php

/**
 * ApiController
 * 
 * This class provides helper methods for API responses.
 */
class ApiController {

    /**
     * Send JSON response
     * 
     * @param mixed $data Data to be encoded as JSON
     * @param int $statusCode HTTP status code (default: 200)
     */
    public function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Send JSON error response
     * 
     * @param string $message Error message
     * @param int $statusCode HTTP status code
     * @param array $errors Optional array of specific field errors
     */
    public function sendError($message, $statusCode, $errors = []) {
        $errorResponse = [
            'error' => [
                'message' => $message,
                'status_code' => $statusCode,
            ]
        ];

        if (!empty($errors)) {
            $errorResponse['error']['errors'] = $errors;
        }

        $this->sendResponse($errorResponse, $statusCode);
    }
}
?>
