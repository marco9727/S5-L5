<?php
session_start();
require_once './config.php';
require_once './Database.php';
require_once './User.php';
require_once './Auth.php';
require_once './AdminPanel.php';

$db = new Database();
$auth = new Auth(new User($db->getConnection()));

if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (!$auth->isAdmin()) {
    echo "Access denied.";
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid book ID.";
    exit;
}

$adminPanel = new AdminPanel($db->getConnection());
$book = $adminPanel->getBookById($_GET['id']);

if (!$book) {
    echo "Book not found.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $adminPanel->deleteBook($_GET['id']);
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Book Details</h1>
        <div>
            <h3><?php echo htmlspecialchars($book['titolo']); ?></h3>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['autore']); ?></p>
            <p><strong>Genre:</strong> <?php echo htmlspecialchars($book['genere']); ?></p>
            <p><strong>Published Year:</strong> <?php echo htmlspecialchars($book['anno_pubblicazione']); ?></p>
        </div>
        <form method="post">
            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
            <a href="admin_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
        </form>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>