<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
    $username = $_SESSION['nome_utente'];

    $check_author_sql = "SELECT author FROM post WHERE id = $post_id LIMIT 1";
    $check_author_result = $conn->query($check_author_sql);
    $author_row = $check_author_result->fetch_assoc();
    $author = $author_row['author'];

    if ($author === $username) {
        $delete_post_sql = "DELETE FROM post WHERE id = $post_id";
        if ($conn->query($delete_post_sql) === TRUE) {
            header("Location: forum.php");
            exit;
        } else {
            echo "Errore durante l'eliminazione del post: " . $conn->error;
        }
    } else {
        echo "Non sei l'autore di questo post. Non puoi eliminarlo.";
    }
}

$conn->close();
?>
