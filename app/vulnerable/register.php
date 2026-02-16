<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione Utente</title>
    <link rel="stylesheet" href="css/stile.css">
</head>
<body>
    <h1>Registrazione Utente</h1>
    <form action="process.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br>

        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome" required><br>

        <label for="eta">Età:</label>
        <input type="number" id="eta" name="eta" required><br>

        <label for="nome_utente">Nome utente:</label>
        <input type="text" id="nome_utente" name="nome_utente" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" name="register" value="Registrati">
    </form>
</body>
</html>
