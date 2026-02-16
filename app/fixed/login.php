<!DOCTYPE html>
<html lang="it">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accesso Utente</title>
    <link rel="stylesheet" href="css/stile.css">
</head>
<body>
    <h1>Accesso Utente</h1>
    <form action="process.php" method="post">
        <label for="nome_utente">Nome utente:</label>
        <input type="text" id="nome_utente" name="nome_utente" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Accedi">
    </form>
</body>
</html>



