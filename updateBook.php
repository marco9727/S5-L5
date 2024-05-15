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
$book = $adminPanel->getBookById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $published_year = $_POST['published_year'];

    if ($adminPanel->updateBook($id, $title, $author, $genre, $published_year)) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Error updating book.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Edit Book</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form class="mt-3" method="POST" action="update_book.php?id=<?php echo $id; ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['titolo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author:</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['autore']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre:</label>
                <input type="text" class="form-control" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genere']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="published_year" class="form-label">Published Year:</label>
                <input type="number" class="form-control" id="published_year" name="published_year" value="<?php echo htmlspecialchars($book['anno_pubblicazione']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
        <a href="index.php" class="mt-3">Back to Dashboard</a>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>