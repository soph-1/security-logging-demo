<?php

include 'logger.php';

session_start();

$username = $_SESSION['nome_utente'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])  && $_POST['logout'] === '1') {
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

logMessage("INFO", "Logout effettuato", $username);
session_destroy();

header("Location: index.php");
exit;
}
?>
