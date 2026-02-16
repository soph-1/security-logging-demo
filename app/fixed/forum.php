<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "Adm1n";
$dbname = "Project";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

$sql = "SELECT id, title, content, author, date FROM post ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum</title>
    <link rel="stylesheet" href="css/stile.css">
</head>
<body>
    <h1 class="forum-title">Forum</h1> 
    <header>
        <div class="button-container">
            <form action="logout.php" method="post" onsubmit="setLogoutSubmitted()">
                <input type="hidden" name="logout" value="1">
                <input type="submit" value="Logout" class="button">
            </form>
            <form action="remove2.php" method="post">
                <input type="submit" value="Rimuovi account" class="button">
            </form>
        </div>
    </header>

    <div class="main-content">
        <form action="create_post.php" method="post">
            <input type="submit" value="Crea un nuovo post" class="create-post-button">
        </form>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="post">';
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<div class="author-date">Scritto da ' . htmlspecialchars($row['author']) . ' il ' . htmlspecialchars($row['date']) . '</div>';
                echo '<p>' . nl2br(htmlspecialchars($row['content'])) . '</p>';
                if ($_SESSION['nome_utente'] === $row['author']) {
                    echo '<form action="elimina_post.php" method="post">';
                    echo '<input type="hidden" name="post_id" value="' . $row['id'] . '">';
                    echo '<input type="submit" value="Elimina" class="delete-post-button">';
                    echo '</form>';
                }
                echo '</div>';
            }
        } else {
            echo '<p>Non ci sono post da visualizzare.</p>';
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
