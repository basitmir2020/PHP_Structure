<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? esc_html($title) : 'Login'; ?></title>
    <?php // This view is rendered as standalone by LoginController, 
          // so it won't use the main __Header.tpl unless explicitly included here.
          // For a true standalone page, you might have specific CSS links here.
    ?>
    <style>
        body { font-family: sans-serif; margin: 40px; background-color: #f4f4f4; }
        .container { background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        label { display: block; margin-bottom: 8px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 3px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 10px 15px; border: none; border-radius: 3px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="<?php echo WEBPATH; ?>login/process" method="POST"> <?php // Action URL is placeholder ?>
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <?php if(isset($arg) && !empty($arg)): ?>
            <p style="color:red;">Error: <?php echo esc_html($arg); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
