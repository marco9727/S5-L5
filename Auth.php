<?php
class Auth {
    private $user;

    public function __construct($user) {
        $this->user = $user;
    }

    public function login($username, $password) {
        $user = $this->user->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    public function getUser() {
        if ($this->isLoggedIn()) {
            // Ottieni le informazioni dell'utente dal database
            $userId = $_SESSION['user_id'];
            return $this->user->getUserById($userId);
        }
        return null;
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        session_destroy();
    }
}
?>