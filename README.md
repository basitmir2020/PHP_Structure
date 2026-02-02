# PHP MVC and API Framework

This project is a PHP application built to serve as a foundational framework for developing web applications and JSON-based APIs. It emphasizes a namespaced, layered architecture following principles similar to Model-View-Controller (MVC), promoting separation of concerns and maintainability.

## Getting Started

Follow these instructions to get the project up and running on your local machine.

### Prerequisites

*   **PHP 7.4+**: Ensure you have PHP installed and added to your system's PATH.
*   **Web Server**: Apache or Nginx.
    *   **Apache**: Ensure `mod_rewrite` is enabled.
*   **Database**: MySQL or MariaDB.

### Installation

1.  **Clone the Repository**:
    ```bash
    git clone <repository-url>
    ```
2.  **Configure Web Server**:
    *   Point your web server's document root to the project folder (e.g., `c:\Users\basit\Downloads\PHP_Structure`).
    *   If using Apache, the included `.htaccess` file should handle URL rewriting automatically.

### Database Setup

Since this project does not include a migration system or initial SQL dump, you need to create the database and tables manually.

1.  **Create Database**: Create a new database in MySQL (e.g., `php_structure_db`).
2.  **Create Tables**: Run the following SQL command to create the `accounts` table (required for the demo API):

    ```sql
    CREATE TABLE IF NOT EXISTS accounts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE
    );
    ```

### Configuration

1.  Open `config.tpl` in the root directory.
2.  **Database Configuration**: Update the database connection settings at the bottom of the file:
    ```php
    define('DBHOST', 'localhost');
    define('DBUSER', 'root'); // Your MySQL username
    define('DBPASS', '');     // Your MySQL password
    define('DBNAME', 'php_structure_db'); // Your database name
    ```
3.  **URL Configuration**: Ensure `WEBPATH` matches your local server environment:
    ```php
    define("WEBPATH", "http://localhost/PHP_Structure/");
    ```
4.  **API Keys**: Configure valid API keys for authentication:
    ```php
    define('VALID_API_KEYS', ['your-super-secret-api-key-12345']);
    ```

## Project Architecture

**Key Architectural Features:**

*   **Namespaced Codebase:** Organized under the `App\` namespace with PSR-4 autoloading.
*   **Layered Design:**
    *   **Controllers (`App\Controller`, `App\Http\Api`):** Handle incoming HTTP requests.
    *   **Services (`App\Service`):** Encapsulate core business logic.
    *   **Models (`App\Model`):** Represent data entities.
    *   **Persistence (`App\Persistence`):** `DbContext` handles secure database interactions.
*   **Routing:** Custom routing for both web (`App\Core\CoreRouter`) and API (`App\Http\ApiRouter`) requests.
*   **Security:**
    *   PDO prepared statements for SQL injection prevention.
    *   API Key authentication.
    *   Secure session and cookie management.
    *   input validation and XSS protection helpers.

### Project Structure

*   **`app/`**: Root directory for all namespaced application code (`App\`).
    *   **`Core/`**: Framework essentials (`CoreRouter`, `ViewManager`).
    *   **`Controller/`**: Web controllers (`Admin/`, `Public/`).
    *   **`Http/`**: API handling (`Api/`, `ApiRouter`).
    *   **`Interfaces/`**: Service contracts.
    *   **`Model/`**: Data entities (`AccountModel`).
    *   **`Persistence/`**: Database access (`DbContext`).
    *   **`Service/`**: Business logic (`AccountService`).
    *   **`Util/`**: Utilities (`SessionManager`, `Validator`).
*   **`view/`**: `.tpl` template files (`admin/`, `public/`).
*   **`includes/`**: Shared public HTML partials.
*   **`Admin/`**: Admin portal entry point and assets.
*   **`catalog/`**: Procedural helper functions.
*   **`config.tpl`**: Main application configuration.
*   **`index.php`**: Main entry point.

## Basic API Usage

The base path for all API routes is `/api`.

### Authentication

Include your API key in the `X-API-KEY` header:

```bash
curl -H "X-API-KEY: your-super-secret-api-key-12345" http://localhost/PHP_Structure/api/accounts
```

### Accounts Endpoint

*   **GET** `/api/accounts`: List all accounts.
*   **GET** `/api/accounts/{id}`: Get account by ID.
*   **POST** `/api/accounts`: Create a new account.
    *   Body: `{ "name": "John", "email": "john@example.com" }`
