<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="manifest" href="<?= base_url()?>manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rumah Sakit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f4f8;
        }
        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .login-container input[type="text"], 
        .login-container input[type="password"] {
            width: calc(100% - 40px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-container button {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: calc(100% - 40px);
        }
        .login-container button:hover {
            background: #0056b3;
        }
        .login-container p {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .login-container p a {
            color: #007bff;
            text-decoration: none;
        }
        .login-container p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>RSUA WEBB APP LOGIN</h2>
        <input type="text" placeholder="Username" name="username" required>
        <input type="password" placeholder="Password" name="password" required>
        <button>Login</button>
        <p>Belum punya akun? <a href="#">Daftar di sini</a></p>
    </div>

</body>
</html>
