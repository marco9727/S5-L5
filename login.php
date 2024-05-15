<?php
session_start();
require_once './config.php';
require_once './Database.php';
require_once './User.php';
require_once './Auth.php';

$db = new Database();
$auth = new Auth(new User($db->getConnection()));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($auth->login($username, $password)) {
        // Controlla se l'utente è un amministratore
        if ($auth->isAdmin()) {
            header('Location: index.php');
        } else {
            header('Location: user_dashboard.php'); // Aggiungi questa riga
        }
        exit;
    } else {
        $error = "Username or password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .wrapper {
            width: 360px;
            padding: 20px;
            margin: auto;
        }
        .wrapper label {
            display: block;
            margin-bottom: 5px;
        }
        .wrapper input[type="text"],
        .wrapper input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .wrapper button[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .wrapper button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1 class="mt-5">Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form class="mt-3" method="POST" action="login.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="mt-3">Non hai un account? <a href="register.php">Registrati qui</a>.</p>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>