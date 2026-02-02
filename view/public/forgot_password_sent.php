<!DOCTYPE html>
<html>

<head>
    <title>Email Sent</title>
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
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Check your Inbox</h2>
        <p><?php echo htmlspecialchars($arg); ?></p>
        <p><a href="<?php echo WEBPATH; ?>public/login">Back to Login</a></p>
    </div>
</body>

</html>