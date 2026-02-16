<?php
include 'logger.php';

session_start();

$host = "localhost";
$username = "root";
$password = "Adm1n";
$dbname = "Project";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $eta = $_POST['eta'];
    $nome_utente = $_POST['nome_utente'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $check_user_sql = $conn->prepare("SELECT * FROM utenti WHERE nome_utente = ?");
    $check_user_sql->bind_param("s", $nome_utente);
    $check_user_sql->execute();
    $result = $check_user_sql->get_result();

    if ($result->num_rows > 0) {
        echo "Nome utente già utilizzato. <a href='register.php'>Prova con un altro nome utente</a>.";
    } else {
        $register_sql = $conn->prepare("INSERT INTO utenti (nome, cognome, eta, nome_utente, password) VALUES (?, ?, ?, ?, ?)");
        $register_sql->bind_param("ssiss", $nome, $cognome, $eta, $nome_utente, $hashed_password);
        
        if ($register_sql->execute() === TRUE) {
            $_SESSION['logged_in'] = true;
            $_SESSION["nome_utente"] = $nome_utente;
            logMessage("INFO", "Registrazione avvenuta con successo", $nome_utente);
            header("Location: forum.php");
            exit;
        } else {
            logMessage("ERROR", "Errore durante la registrazione", $nome_utente);
            echo "Errore durante la registrazione. Riprova più tardi.";
        }
    }
    $check_user_sql->close();
    $register_sql->close();
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_utente = $_POST["nome_utente"];
    $password = $_POST['password'];

    $login_sql = $conn->prepare("SELECT * FROM utenti WHERE nome_utente = ?");
    $login_sql->bind_param("s", $nome_utente);
    $login_sql->execute();
    $login_result = $login_sql->get_result();

    if ($login_result->num_rows > 0) {
        $row = $login_result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION["nome_utente"] = $nome_utente;
            logMessage("INFO", "Accesso riuscito", $nome_utente);
            header("Location: forum.php");
            exit;
        } else {
            logMessage("ERROR", "Accesso negato", $nome_utente);
            echo "Credenziali errate.<a href='login.php'>Riprova</a> oppure <a href='register.php'>Registrati</a>.";
        }
    } else {
        logMessage("ERROR", "Accesso negato", $nome_utente);
        echo "Questo account non esiste.<a href='login.php'>Riprova</a> oppure <a href='register.php'>Registrati</a>.";
    }
    $login_sql->close();
}

$conn->close();
require_once 'analize.php';
?>
