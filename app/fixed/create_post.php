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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $author = $_SESSION['nome_utente'];
    $date = date("Y-m-d H:i:s");

    if ($conn->connect_error) {
        die("Connessione al database fallita: " . $conn->connect_error);
    }

    $insert_post_sql = "INSERT INTO post (title, content, author, date) VALUES ('$title', '$content', '$author', '$date')";
    if ($conn->query($insert_post_sql) === TRUE) {
        header("Location: forum.php");
        exit;
    } else {
        echo "Errore durante l'inserimento del post nel database: " . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Nuovo Post</title>
    <link rel="stylesheet" href="css/stile.css">
</head>
<body>
    <h1>Crea Nuovo Post</h1>
    
    <div class="create-post-form">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="title">Titolo:</label><br>
            <input type="text" id="title" name="title" required><br>
            
            <label for="content">Contenuto:</label><br>
            <textarea id="content" name="content" rows="4" cols="50" required></textarea><br>
            
            <input type="submit" name="submit" value="Invia">
        </form>
    </div>
    
    <div class="back-to-forum">
        <a href="forum.php" class="button">Torna al Forum</a>
    </div>
</body>
</html>