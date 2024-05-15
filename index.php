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

$adminPanel = new AdminPanel($db->getConnection());
$books = $adminPanel->getBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Admin Dashboard</h1>
        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
        <h2 class="mt-5">Books</h2>
        <ul class="list-group">
            <?php foreach ($books as $book): ?>
                <li class="list-group-item">
                    <?php echo htmlspecialchars($book['titolo']); ?> by <?php echo htmlspecialchars($book['autore']); ?>
                    <a href="book_details.php?id=<?php echo $book['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="createBook.php" class="btn btn-success mt-3">Add New Book</a>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>