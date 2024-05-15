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

$adminPanel = new AdminPanel($db->getConnection());
$id = $_GET['id'];

if ($adminPanel->deleteBook($id)) {
    header('Location: index.php');
    exit;
} else {
    $error = "Error deleting book.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Delete Book</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary mt-3">Back to Dashboard</a>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>