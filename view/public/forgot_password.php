<!DOCTYPE html>
<html>

<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 50px;
            background: #f4f6f8;
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 8px;
            width: 350px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Reset Password</h2>
        <p>Enter your email address to receive a reset link.</p>

        <?php if (isset($arg) && !empty($arg)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($arg); ?></p>
        <?php endif; ?>

        <form action="<?php echo WEBPATH; ?>public/forgotpassword/process" method="POST">
            <input type="text" name="email" placeholder="Enter email" required>
            <button type="submit">Send Reset Link</button>
        </form>
        <p><a href="<?php echo WEBPATH; ?>public/login">Back to Login</a></p>
    </div>
</body>

</html>