<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'Login'; ?></title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 50px;
            background-color: #f4f6f8;
        }

        .login-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        h1 {
            margin-top: 0;
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: #dc3545;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .links {
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
        }

        .links a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h1>Login</h1>

        <?php if (isset($arg) && !empty($arg)): ?>
            <div class="error"><?php echo htmlspecialchars($arg); ?></div>
        <?php endif; ?>

        <form action="<?php echo WEBPATH; ?>public/login/process" method="POST">
            <div class="form-group">
                <label>Email Address</label>
                <input type="text" name="email" required placeholder="admin@example.com">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="admin123">
            </div>
            <button type="submit">Sign In</button>
        </form>

        <div class="links">
            <a href="<?php echo WEBPATH; ?>public/forgotpassword">Forgot Password?</a>
        </div>
    </div>
</body>

</html>