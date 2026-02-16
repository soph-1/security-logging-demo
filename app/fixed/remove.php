<?php
include 'logger.php';

session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: forum.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "Adm1n";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $nome_utente = $_SESSION['nome_utente'];
        $rem_sql = "DELETE FROM utenti WHERE nome_utente = '$username'";

        if ($conn -> query($rem_sql) === TRUE) {
        session_unset();
        session_destroy();

        logMessage("INFO", "Account rimosso", $nome_utente);
        header("Location: index.php");
        exit;
    } else {
        logMessage("ERROR", "Errore durante la rimozione dell'account", $nome_utente);
        echo "Errore durante la rimozione dell'account: " . $conn->error;
    }
}
$conn->close();
?>


