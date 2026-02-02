# Database Documentation

The framework uses a lightweight PDO wrapper to ensure security and ease of use. It supports both standard SQL execution and efficient Stored Procedure calls.

## 1. DbContext (`app/Persistence/DbContext.php`)

Used for standard CRUD operations (SELECT, INSERT, UPDATE, DELETE).

### Initialization
```php
use App\Persistence\DbContext;

$db = new DbContext();
```

### Methods
*   **select($sql, $params)**: Returns array of rows.
    ```php
    $users = $db->select("SELECT * FROM users WHERE active = ?", [1]);
    ```
*   **insert($sql, $params)**: Returns true/false.
    ```php
    $db->insert("INSERT INTO logs (message) VALUES (?)", ['Test']);
    ```
*   **update($sql, $params)**: Returns true if rows affected > 0.
*   **delete($sql, $params)**: Returns true if rows affected > 0.

---

## 2. ProcedureContext (`app/Persistence/ProcedureContext.php`)

Specialized class for executing Stored Procedures and SQL Functions efficiently.

### Initialization
```php
use App\Persistence\DbContext;
use App\Persistence\ProcedureContext;

$db = new DbContext();
$proc = new ProcedureContext($db->getConnection());
```

### Calling Stored Procedures (`call`)
Use this when you expect a result set (rows) or just an action.
```php
// Call sp_GetUsersByRole('admin')
$admins = $proc->call('sp_GetUsersByRole', ['admin']);
```

### Calling SQL Functions (`executeFunction`)
Use this when you need a single return value (scalar).
```php
// Call fn_CalculateTax(100, 0.2)
$tax = $proc->executeFunction('fn_CalculateTax', [100, 0.2]);
```
