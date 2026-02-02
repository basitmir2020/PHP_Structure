# Routing & Architecture

The framework follows a **Modular MVC** pattern.

## Directory Structure
```
app/
├── Config/         # Configuration & Bootstrap
├── Controller/     # Controllers grouped by Module
│   ├── Public/     # e.g., HomeController
│   └── Admin/      # e.g., DashboardController
├── Core/           # Core Framework Logic (Router, ViewManager)
├── Interfaces/     # Service Interfaces
├── Persistence/    # Database Contexts
├── Service/        # Business Logic
└── Util/           # Utilities (Session, Security, Validator)
```

## Routing Logic
The requested URL is parsed automatically:
`http://domain.com/MODULE/CONTROLLER/METHOD/ARGS`

### Examples
*   `/public/home/index` -> Calls `App\Controller\Public\HomeController::index()`
*   `/admin/users/edit/5` -> Calls `App\Controller\Admin\UsersController::edit(5)`

### Default Module
If no module is specified, the framework falls back to the default module defined in `app/Config/Modules.php`.

## Views
Views are located in `view/{module}/{controller}/{method}.php`.
They are rendered using `ViewManager`.

```php
// In Controller
$this->viewManager->body('public/home/index', 'Page Title', $data);
```
