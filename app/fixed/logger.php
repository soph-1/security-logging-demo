<?php
$logFile = 'log.log';

function getUserIP() {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function logMessage($level, $message, $nome_utente, $ip = null) {
    global $logFile;
    if ($ip === null) {
        $ip = getUserIP();
    }

    $date = date('Y-m-d H:i:s');
    $logEntry = "$date | $level | $message | User: $nome_utente | IP: $ip" . PHP_EOL;

    $fileHandle = fopen($logFile, 'a');

    if ($fileHandle) {
        fwrite($fileHandle, $logEntry);
        fclose($fileHandle);
    } else {
        echo "Errore: impossibile aprire il file.";
    }
}
?>
