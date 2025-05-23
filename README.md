# PHP_Structure

## Basic API Usage

This project now includes API endpoints for accessing and managing resources.
The base path for all API routes is `/api`.

### Accounts Endpoint (`/api/accounts`)

#### GET `/api/accounts`

*   **Description**: Retrieves a list of all accounts.
*   **Example Response (Success 200 OK)**:
    ```json
    [
        {
            "id": "1",
            "name": "John Doe",
            "email": "john.doe@example.com"
        },
        {
            "id": "2",
            "name": "Jane Smith",
            "email": "jane.smith@example.com"
        }
    ]
    ```

#### GET `/api/accounts/{id}`

*   **Description**: Retrieves a specific account by its ID.
*   **Example URL**: `/api/accounts/1`
*   **Example Response (Success 200 OK)**:
    ```json
    {
        "id": "1",
        "name": "John Doe",
        "email": "john.doe@example.com"
    }
    ```
*   **Example Response (Error 404 Not Found)**:
    ```json
    {
        "error": {
            "message": "Account not found",
            "status_code": 404,
            "errors": []
        }
    }
    ```

#### POST `/api/accounts`

*   **Description**: Creates a new account.
*   **Request Body (application/json)**:
    ```json
    {
        "name": "Samuel Green",
        "email": "sam.green@example.com"
    }
    ```
*   **Example Response (Success 201 Created)**:
    ```json
    {
        "message": "Account created successfully"
        // "id": 3 // ID might be included if dbContext is updated
    }
    ```
*   **Example Response (Error 400 Bad Request - Missing Fields)**:
    ```json
    {
        "error": {
            "message": "Missing required fields for creating account",
            "status_code": 400,
            "errors": []
        }
    }
    ```

---
This is basic documentation for the API. More details and endpoints will be added as the project evolves.