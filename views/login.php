<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #07272D;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }
        .btn-custom {
            background-color: #E0E0E0;
            color: black;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
        <img src="https://athleticmap.com/images/logo-atm.png" alt="Athletic Map Logo" class="logo">
        <h2 class="text-center">Login</h2>
        <form method="POST" action="/pre-treino-rfc/controllers/routerLogin.php">
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="e-mail" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" class="form-control" name="senha" required>
            </div>
            <button type="submit" class="btn btn-custom w-100">Entrar</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
