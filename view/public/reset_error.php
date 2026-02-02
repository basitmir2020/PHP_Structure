<!DOCTYPE html>
<html>

<head>
    <title>Error</title>
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
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="box">
        <h3>Link Expired or Invalid</h3>
        <p><?php echo htmlspecialchars($arg); ?></p>
        <p><a href="<?php echo WEBPATH; ?>public/login">Back to Login</a></p>
    </div>
</body>

</html>