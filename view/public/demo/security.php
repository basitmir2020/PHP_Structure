<div class="container" style="padding: 20px;">
    <h1>Security Features</h1>
    <p>Security is built-in, not an afterthought.</p>

    <hr>

    <h3>1. XSS Prevention</h3>
    <p>Always output user data using <code>App\Util\Security::escape()</code>.</p>
    <div class="alert alert-info">
        Example: <code>echo \App\Util\Security::escape($_GET['name']);</code>
    </div>

    <h3>2. CSRF Protection</h3>
    <p>Every POST form must include a CSRF token.</p>

    <div class="card mb-3">
        <div class="card-header">Live Example</div>
        <div class="card-body">
            <p>Your current session token is:</p>
            <pre><?php echo $arg['token']; ?></pre>

            <p><strong>HTML Implementation:</strong></p>
            <pre><code>
&lt;form method="POST" action="/submit"&gt;
    &lt;input type="hidden" name="csrf_token" 
           value="&lt;?php echo \App\Util\Security::generateCSRFToken(); ?&gt;"&gt;
&lt;/form&gt;
</code></pre>

            <p><strong>Controller Validation:</strong></p>
            <pre><code>
if (!\App\Util\Security::validateCSRFToken($_POST['csrf_token'])) {
    die("Invalid Token");
}
</code></pre>
        </div>
    </div>

    <br>
    <a href="<?php echo WEBPATH; ?>public/demo" class="btn btn-secondary">&larr; Back to Demo</a>
</div>