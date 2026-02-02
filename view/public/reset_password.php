<!DOCTYPE html>
<html>

<head>
    <title>Set New Password</title>
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
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Set New Password</h2>

        <?php if (isset($arg['error'])): ?>
            <p style="color:red;"><?php echo htmlspecialchars($arg['error']); ?></p>
        <?php endif; ?>

        <form action="<?php echo WEBPATH; ?>public/resetpassword/process" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($arg['token']); ?>">

            <label>New Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Change Password</button>
        </form>
    </div>
</body>

</html>