# PHP MVC and API Framework

This project is a custom-built PHP application structured around the Model-View-Controller (MVC) architectural pattern. It serves as a foundational framework for developing web applications and JSON-based APIs.

Key features include:
*   **Dual Routing System:** Separate routing mechanisms for web pages (`library/routing.tpl`) and API endpoints (`library/apiRouting.tpl` via `/api/` prefix).
*   **Administration Interface:** A dedicated `/Admin` section for administrative tasks, with its own routing and views.
*   **Database Abstraction:** Secure database interactions are handled by a Data Access Object (`persistence/dbContext.tpl`) using PDO with prepared statements to prevent SQL injection.
*   **API Capabilities:** A base API controller (`controller/apiController.tpl`) provides standardized JSON responses and error handling, with a sample API endpoint for account management (`controller/api/accountsApiController.tpl`).
*   **Templating:** Uses `.tpl` files (though appearing as PHP files, they are used as templates) for rendering HTML views, with shared header/footer includes.

The framework is designed to be a starting point for building more complex PHP applications by providing a clear separation of concerns and essential components for web and API development.

### Project Structure

This project follows a Model-View-Controller (MVC) like pattern. Below is an overview of the key directories and files:

*   **`/` (Root Directory)**
    *   `index.php`: The main entry point for the public-facing web application. It initializes configuration and routes requests to the appropriate web controllers via `library/routing.tpl` or API requests via `library/apiRouting.tpl`.
    *   `config.tpl`: Contains global configuration settings, including database credentials, path constants, session initialization, and error reporting levels. It also includes essential library files.
    *   `.htaccess`: (Typically) Used for URL rewriting (e.g., to direct all requests to `index.php`) and other Apache web server configurations.

*   **`Admin/`**: Contains all files related to the administration interface.
    *   `Admin/index.php`: The entry point for the admin section. It has its own configuration loading and routing (`Admin/library/adminRouting.tpl`).
    *   `Admin/includes/`: Admin-specific include files like headers, footers, and navigation templates (`__Admin__Header.tpl`, etc.).
    *   `Admin/library/`: Admin-specific library files, notably `adminRouting.tpl`.
    *   `Admin/view/`: Admin-specific view templates.

*   **`catalog/`**: Contains utility files and common functionalities.
    *   `functions.tpl`: Likely holds general-purpose PHP functions used across the application.
    *   `session.tpl`: Manages session starting and handling.
    *   `validation.tpl`: Could contain data validation functions.

*   **`controller/`**: Contains controller classes that handle business logic and orchestrate responses.
    *   `accountsController.tpl`: (Initially empty, intended for web-based account actions).
    *   **`controller/api/`**: Subdirectory specifically for API controllers.
        *   `accountsApiController.tpl`: Example API controller for managing "accounts" (GET, POST). Responds with JSON.
    *   `apiController.tpl`: A base API controller providing standardized methods for sending JSON responses (`sendResponse`) and errors (`sendError`).

*   **`includes/`**: Contains shared template files for the public-facing website.
    *   `__Header.tpl`: Site-wide header template.
    *   `__Footer.tpl`: Site-wide footer template.
    *   `__Navigation.tpl`: Site-wide navigation template.

*   **`library/`**: Contains core library files for the framework.
    *   `routing.tpl`: Handles URL routing for the main website, directing requests to appropriate views/controllers (primarily for HTML pages).
    *   `apiRouting.tpl`: Handles URL routing for API requests (prefixed with `/api/`), directing them to API controllers.

*   **`persistence/`**: Manages data persistence and database interactions.
    *   `dbContext.tpl`: The data access layer class. It uses PDO and prepared statements for secure database operations (connect, select, insert, update, delete).

*   **`view/`**: Contains view templates for the public-facing website.
    *   `htmlEssentials.tpl`: A class likely responsible for assembling and rendering HTML pages, including headers, footers, and main content by including `.tpl` files from this directory.
    *   `index.tpl`: The main homepage template.
    *   Other `.tpl` files here would represent different pages or views.

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