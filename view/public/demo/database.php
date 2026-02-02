<div class="container" style="padding: 20px;">
    <h1>Database & Stored Procedures</h1>
    <p>The framework provides robust wrappers for PDO interactions.</p>

    <hr>

    <h3>1. ProcedureContext</h3>
    <p>Located in <code>app/Persistence/ProcedureContext.php</code>, this class simplifies calling stored procedures.
    </p>

    <div class="card bg-light mb-3">
        <div class="card-header">Code Example</div>
        <div class="card-body">
            <pre><code>
use App\Persistence\DbContext;
use App\Persistence\ProcedureContext;

$db = new DbContext();
$context = new ProcedureContext($db->getConnection());

// 1. Call a Store Procedure that returns rows
$users = $context->call('sp_GetUsersByRole', ['admin']);

// 2. Execute a SQL Function that returns a single value
$totalSales = $context->executeFunction('fn_CalculateTotalSales', ['2023']);
</code></pre>
        </div>
    </div>

    <h3>2. DbContext</h3>
    <p>Standard CRUD operations are handled via <code>app/Persistence/DbContext.php</code>.</p>

    <div class="card bg-light">
        <div class="card-header">Code Example</div>
        <div class="card-body">
            <pre><code>
$db->select("SELECT * FROM users WHERE id = ?", [1]);
$db->insert("INSERT INTO logs (msg) VALUES (?)", ['Error']);
</code></pre>
        </div>
    </div>

    <br>
    <a href="<?php echo WEBPATH; ?>public/demo" class="btn btn-secondary">&larr; Back to Demo</a>
</div>