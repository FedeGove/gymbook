# 💪 GymBook — Sistema di Prenotazioni Palestra

GymBook è un'applicazione web sviluppata in PHP e MySQL per la gestione e prenotazione di corsi in palestra.

Il progetto permette agli utenti di registrarsi, effettuare il login, visualizzare i corsi disponibili e prenotarsi. È presente anche un pannello amministratore per la gestione dei corsi e delle prenotazioni.

## 🌐 Demo

👉 [Vedi il sito live](https://gymbook.altervista.org)

## ✨ Funzionalità

### Utente

- Registrazione account
- Login
- Password salvate tramite hashing
- Visualizzazione dei corsi disponibili
- Visualizzazione dei posti rimanenti
- Prenotazione di un corso
- Cancellazione di una prenotazione
- Visualizzazione delle proprie prenotazioni

### Amministratore

- Accesso tramite ruolo admin
- Creazione di nuovi corsi
- Gestione dei corsi disponibili
- Visualizzazione delle prenotazioni
- Gestione degli utenti e delle prenotazioni

## 🛠️ Tecnologie utilizzate

- PHP 8
- MySQL
- HTML5
- CSS3
- PDO
- Sessioni PHP

## 🗄️ Database

Il progetto utilizza un database MySQL composto principalmente da tre tabelle.

### `users`

Gestisce gli account degli utenti.

Contiene informazioni come:

- ID
- username
- password hashata
- ruolo

### `courses`

Contiene i corsi disponibili in palestra.

Per ogni corso vengono gestite informazioni come:

- nome
- descrizione
- data e orario
- numero massimo di partecipanti

### `bookings`

Gestisce le prenotazioni degli utenti ai corsi.

Collega gli utenti ai corsi e permette di tenere traccia dello stato della prenotazione.

## 📁 Struttura del progetto

```text
gymbook/
│
├── assets/
│   ├── css/
│   └── ...
│
├── config/
│   └── config.php
│
├── includes/
│
├── pages/
│
├── index.php
└── README.md
```

## 🔐 Autenticazione

Gli utenti possono creare un account ed effettuare il login.

Le password non vengono salvate in chiaro nel database, ma vengono gestite tramite hashing.

Dopo il login viene utilizzata una sessione PHP per mantenere l'utente autenticato durante la navigazione.

Il ruolo dell'utente viene utilizzato per distinguere gli utenti normali dagli amministratori.

## 🗃️ Accesso al database

La connessione al database viene gestita tramite PDO.

Esempio:

```php
$pdo = new PDO(
    "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
    $db_user,
    $db_pass
);
```

PDO permette di gestire la connessione al database e l'esecuzione delle query tramite PHP.

## 🎯 Obiettivo del progetto

GymBook è stato realizzato per approfondire lo sviluppo di applicazioni web dinamiche con PHP e MySQL.

Durante il progetto ho lavorato principalmente su:

- gestione utenti
- autenticazione
- sessioni PHP
- database relazionali
- query SQL
- gestione dei ruoli
- prenotazioni
- utilizzo di PDO
- sviluppo frontend responsive

## 🧪 Test

Il progetto è stato testato verificando il flusso principale:

1. registrazione di un nuovo utente
2. login
3. visualizzazione dei corsi
4. prenotazione di un corso
5. cancellazione della prenotazione
6. login come amministratore
7. gestione dei corsi e delle prenotazioni

## 👤 Autore

Federico Governatori
