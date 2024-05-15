<?php
session_start();
require_once './config.php';
require_once './Database.php';
require_once './User.php';
require_once './Auth.php';

$db = new Database();
$auth = new Auth(new User($db->getConnection()));

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user = $auth->getUser();

// Verifica se l'utente è un amministratore
if ($user['role'] === 'admin') {
    header('Location: admin_dashboard.php');
    exit;
}

// Ottieni l'elenco dei libri
$stmt = $db->getConnection()->prepare("SELECT * FROM libri");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utente Normale Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Benvenuto, <?php echo htmlspecialchars($user['username']); ?>!</h1>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <h2 class="mt-5">Elenco Libri</h2>
        <ul class="list-group">
            <?php foreach ($books as $book): ?>
                <li class="list-group-item"><?php echo htmlspecialchars($book['titolo']); ?> by <?php echo htmlspecialchars($book['autore']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>