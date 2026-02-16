<?php

$tentativiMassimi = 5;
$fileLog = "log.log";
$htaccessFile = ".htaccess";
$filePosizione = "posizione.log";
$fileTentativi = "tentativi.json";

function bloccaIP($ip, $htaccessFile) {
    $htaccess = fopen($htaccessFile, 'a');
    if ($htaccess) {
        fwrite($htaccess, "\nDeny from $ip\n");
        fclose($htaccess);
        return true;
    } else {
        return false;
    }
}

function leggiNuoveRighe($fileLog, $filePosizione) {
    $posizione = 0;

    if (file_exists($filePosizione)) {
        $posizione = (int)file_get_contents($filePosizione);
        if (!is_numeric($posizione) || $posizione < 0) {
            $posizione = 0;
        }
    }
    $file = fopen($fileLog, 'r');
    if ($file === false) {
        return [];
    }
    fseek($file, 0, SEEK_END);
    $fileSize = ftell($file);
    if ($posizione > $fileSize) {
        $posizione = 0;
    }

    fseek($file, $posizione);

    $nuoveRighe = [];
    while (($linea = fgets($file)) !== false) {
        $nuoveRighe[] = $linea;
    }

    $nuovaPosizione = ftell($file);

    if ($nuovaPosizione === false || $nuovaPosizione <= $posizione) {
        $nuovaPosizione = 0;
    }

    file_put_contents($filePosizione, $nuovaPosizione);
    fclose($file);

    return $nuoveRighe;
}

function estraiIP($linea) {
    if (preg_match('/IP: ((?:\d{1,3}\.){3}\d{1,3})/', $linea, $matches)) {
        return $matches[1];
    }
    if (preg_match('/IP: ([A-Fa-f0-9:]+)/', $linea, $matches)) {
        return $matches[1];
    }
    return null;
}

function analizzaLog($nuoveRighe, $tentativiMassimi) {
    static $tentativiFallitiPerIP = [];

    if (file_exists($GLOBALS['fileTentativi'])) {
        $tentativiFallitiPerIP = json_decode(file_get_contents($GLOBALS['fileTentativi']), true);
    }

    $ipDaBloccare = null;

    foreach ($nuoveRighe as $linea) {
        $ip = estraiIP($linea);

        if ($ip && strpos($linea, "Accesso negato") !== false) {
            if (!isset($tentativiFallitiPerIP[$ip])) {
                $tentativiFallitiPerIP[$ip] = 1;
            } else {
                $tentativiFallitiPerIP[$ip]++;
            }

            if ($tentativiFallitiPerIP[$ip] >= $tentativiMassimi) {
                bloccaIP($ip, $GLOBALS['htaccessFile']);
                $ipDaBloccare = $ip;
                unset($tentativiFallitiPerIP[$ip]);
                break; 
            }
        } elseif ($ip && strpos($linea, "Accesso riuscito") !== false) {
            if (isset($tentativiFallitiPerIP[$ip])) {
                unset($tentativiFallitiPerIP[$ip]);
            }
        }
    }

    salvaTentativi($tentativiFallitiPerIP);
    return $ipDaBloccare;
}

function salvaTentativi($tentativiFallitiPerIP) {
    file_put_contents($GLOBALS['fileTentativi'], json_encode($tentativiFallitiPerIP));
}

function main() {
    global $fileLog, $filePosizione, $tentativiMassimi, $htaccessFile;

    $nuoveRighe = leggiNuoveRighe($fileLog, $filePosizione);
    $ipDaBloccare = analizzaLog($nuoveRighe, $tentativiMassimi);
}
main();
?>
