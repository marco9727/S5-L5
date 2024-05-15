<?php
session_start();
require_once './Auth.php';

$auth = new Auth(null);
$auth->logout();

header('Location: login.php');
exit;
?>