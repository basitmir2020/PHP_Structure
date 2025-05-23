<?php
namespace App\Http;

class BaseApiController { // Renamed from ApiController
    // Method to send JSON response
    public function sendResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Method to send JSON error response
    public function sendError($message, $statusCode, $errors = []) {
        $errorResponse = [
            'error' => [
                'message' => $message,
                'status_code' => $statusCode
            ]
        ];
        if (!empty($errors)) {
            $errorResponse['error']['errors'] = $errors;
        }
        $this->sendResponse($errorResponse, $statusCode);
    }
}
?>
