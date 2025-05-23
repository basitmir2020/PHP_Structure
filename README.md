# PHP MVC and API Framework

This project is a PHP application built to serve as a foundational framework for developing web applications and JSON-based APIs. It emphasizes a namespaced, layered architecture following principles similar to Model-View-Controller (MVC), promoting separation of concerns and maintainability.

**Key Architectural Features:**

*   **Namespaced Codebase:** Organized under the `App\` namespace with PSR-4 autoloading.
*   **Layered Design:**
    *   **Controllers (`App\Controller`, `App\Http\Api`):** Handle incoming HTTP requests for web and API routes respectively.
    *   **Services (`App\Service`):** Encapsulate core business logic, interacting with the persistence layer. Interfaces are defined in `App\Interfaces\Service`.
    *   **Models (`App\Model`):** Represent data entities (e.g., `AccountModel`).
    *   **Persistence (`App\Persistence`):** `DbContext` class handles database interactions using PDO with prepared statements for security.
*   **Routing:**
    *   `App\Core\CoreRouter`: Manages routing for web pages (public and admin).
    *   `App\Http\ApiRouter`: Manages routing for API requests (prefixed `/api/`).
*   **View Rendering:**
    *   `App\Core\ViewManager`: Handles rendering of `.tpl` template files, supporting layouts with headers/footers for different site sections (public, admin).
*   **Admin Portal:** A dedicated section for administration, leveraging the same core components but with its own routing configurations and templates.
*   **Security Enhancements:**
    *   SQL Injection prevention via PDO prepared statements.
    *   API Key authentication for API endpoints.
    *   Secure session management (HttpOnly, Secure, SameSite flags; session ID regeneration).
    *   Helper for secure cookie management.
    *   Input validation (`App\Util\Validator`).
    *   Output encoding (`esc_html()` helper) for XSS prevention in views.
    *   Standard HTTP security headers (CSP, X-Frame-Options, etc.).

The framework provides a structured starting point for building more complex PHP applications with a focus on modern practices and security.

### Project Structure

This project now follows a namespaced, layered architecture with a clear separation of concerns. The primary application code resides within the `app/` directory, adhering to PSR-4 autoloading for the `App\` namespace.

*   **`app/`**: Root directory for all namespaced application code.
    *   **`Core/`**: Contains core framework classes essential for application operation.
        *   `CoreRouter.php`: Handles routing for web pages (public and admin), dispatching requests to appropriate controllers.
        *   `ViewManager.php`: Manages view template rendering, including headers, footers, and navigation partials.
    *   **`Controller/`**: Houses web controller classes, organized by module.
        *   `Admin/`: Controllers specific to the Admin portal (e.g., `AuthController.php`).
        *   `Public/`: Controllers for the public-facing website (e.g., `HomeController.php`, `LoginController.php`).
    *   **`Http/`**: Contains classes related to HTTP request and response handling, primarily for the API.
        *   `Api/`: API resource controllers (e.g., `AccountsController.php`).
        *   `ApiRouter.php`: Handles routing for all API requests (prefixed with `/api/`).
        *   `BaseApiController.php`: Provides common methods for API controllers to send JSON responses and errors.
    *   **`Interfaces/`**: Defines PHP interfaces, promoting contract-based programming.
        *   `Service/`: Interfaces for service layer classes (e.g., `IAccountService.php`).
    *   **`Model/`**: Contains data model classes representing application entities.
        *   `AccountModel.php`: Example model for account data.
    *   **`Persistence/`**: Manages data persistence and database interactions.
        *   `DbContext.php`: Data access layer using PDO and prepared statements for secure database operations.
    *   **`Service/`**: Contains business logic service classes.
        *   `AccountService.php`: Example service implementing `IAccountService` for account-related operations.
    *   **`Util/`**: Utility classes providing common functionalities.
        *   `CookieManager.php`: For setting, getting, and deleting cookies securely.
        *   `SessionManager.php`: For managing user sessions with enhanced security.
        *   `Validator.php`: For validating input data.

*   **`view/`**: Contains all `.tpl` template files for rendering HTML, organized by module.
    *   `admin/`: Template files for the Admin portal (e.g., `index.tpl` for login, `404.tpl`).
    *   `public/`: Template files for the public-facing site (e.g., `index.tpl`, `login.tpl`, `404.tpl`).

*   **`includes/`**: Contains shared HTML partials (header, footer, navigation) for the public-facing website (e.g., `__Header.tpl`).

*   **`Admin/`**:
    *   `index.php`: Entry point for the Admin portal.
    *   `includes/`: Contains shared HTML partials specific to the Admin portal (e.g., `__Admin__Header.tpl`).
    *   `.htaccess`: Apache configuration specific to the admin directory (if any).

*   **`catalog/`**: Contains remaining procedural helper files.
    *   `functions.tpl`: Global utility functions (includes `esc_html()`).
    *   `validation.tpl`: Legacy procedural validation functions (functionality largely superseded by `App\Util\Validator` for new code).

*   **`config.tpl`**: Main application configuration file. Initializes constants, PSR-4 autoloader, session, security headers, and includes essential procedural files.

*   **`index.php`**: Main entry point for the public website and API. Detects API calls and routes to `ApiRouter` or web `CoreRouter`.

*   **`.htaccess`**: Root Apache configuration file, typically for URL rewriting to `index.php`.

## Basic API Usage

This project now includes API endpoints for accessing and managing resources.
The base path for all API routes is `/api`.

### Authentication

All API requests require authentication via an API key. The API key must be included in the `X-API-KEY` header of your request.

Example using `curl`:

```bash
curl -H "X-API-KEY: your-super-secret-api-key-12345" http://localhost/PHP_Structure/api/accounts
```

Replace `your-super-secret-api-key-12345` with a valid API key. Requests without a valid API key will receive a `401 Unauthorized` error.

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