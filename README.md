# OWASP A09:2021 – Security Logging and Monitoring Failures

## 📌 Overview

Questo progetto dimostra una vulnerabilità riconducibile alla categoria OWASP Top 10 – A09:2021 Security Logging and Monitoring Failures.

L'applicazione web PHP analizzata presenta una gestione non sicura del sistema di logging, che permette la manipolazione dei log tramite Log Injection.

L'obiettivo del progetto è:

- Dimostrare come un sistema di logging mal implementato possa essere sfruttato
- Simulare l'attacco tramite intercettazione del traffico HTTP
- Implementare contromisure per garantire l'integrità dei log
- Integrare un sistema di protezione contro attacchi di Brute Force

Il progetto include sia la versione vulnerabile che quella corretta dell'applicazione.

## 🛠 Strumenti utilizzati

- XAMPP (Apache HTTP Server, PHP, MariaDB)
- Burp Suite (Proxy) per l'intercettazione e modifica del traffico HTTP
- Ambiente locale di test controllato

## 🧪 Vulnerabilità dimostrata

### 🔎 Log Injection

L'applicazione registra i tentativi di accesso nel file di log tramite una funzione personalizzata:
```php
function logMessage($level, $message, $nome_utente, $ip = null)
```

Il parametro `$nome_utente` non viene sanitizzato prima di essere scritto nel file di log. Attraverso Burp Proxy è possibile modificare la richiesta HTTP e inserire caratteri speciali come `\n` (newline).

Questo permette di:

- Inserire nuove righe arbitrarie nel file di log
- Manipolare il contenuto dei log esistenti
- Simulare eventi falsi (es. accessi riusciti inesistenti)
- Compromettere l'integrità del sistema di monitoraggio

## 🔐 Sistema di Protezione Implementato

Nella directory `secure/` sono state implementate le seguenti difese:

- ✔ **Sanitizzazione input**: Rimozione o neutralizzazione di caratteri speciali e prevenzione di newline injection tramite filtri PHP
- ✔ **Protezione Brute Force**: Blocco dell'indirizzo IP dopo 5 tentativi falliti, sistema di monitoraggio tramite file JSON e reset del contatore dopo un login riuscito
- ✔ **Logging Sicuro**: Migliore gestione delle stringhe prima della scrittura su file per prevenire manipolazioni esterne

## 📂 Struttura della Repository
```
security-logging-demo/
│
├── vulnerable/      → Versione vulnerabile dell'applicazione
├── secure/          → Versione con difese implementate
├── logs/
│   ├── sample_vulnerable.log
│   └── sample_secure.log
├── docs/
│   └── relazione.pdf
└── README.md
```

## 🚀 Come testare il progetto

1. Installare XAMPP e avviare il modulo Apache
2. Copiare la cartella del progetto in `htdocs/`
3. Accedere via browser a: `http://localhost/vulnerable/login.php`
4. Utilizzare Burp Suite per intercettare la richiesta POST di login
5. Modificare il parametro `nome_utente` inserendo caratteri di newline ed esaminare il file di log risultante
6. Ripetere il test con la cartella `secure/` per verificare l'efficacia delle contromisure

## 📖 Contenuto Accademico e Obiettivi

Il progetto include una relazione tecnica dettagliata che copre:

- **Analisi tecnica**: Descrizione dello scenario e del codice vulnerabile
- **Fase di attacco**: Walkthrough dei passaggi per replicare la vulnerabilità
- **Difesa**: Spiegazione dell'implementazione delle contromisure
- **Concetti chiave**: CWE-117 (Improper Output Neutralization for Logs), Input Validation e Brute Force Mitigation

## ⚠️ Disclaimer

Questo progetto è stato sviluppato esclusivamente a scopo didattico e formativo all'interno di un ambiente locale controllato.

L'obiettivo è illustrare le vulnerabilità di sicurezza e le relative contromisure per scopi di ricerca e apprendimento professionale. L'autore non si assume alcuna responsabilità per l'uso improprio delle informazioni, delle tecniche o del codice contenuti in questa repository.

L'esecuzione di test di penetrazione o tentativi di exploit su sistemi senza esplicita autorizzazione è illegale. Si prega di agire sempre in modo etico e nel rispetto delle normative vigenti.

## 👨‍💻 Autore

Progetto realizzato per l'approfondimento della sicurezza applicativa e degli standard OWASP.
