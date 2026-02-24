# 💪 GymBook — Sistema di Prenotazioni Palestra

Un'applicazione web per la gestione e prenotazione di corsi in palestra, 
sviluppata in PHP e MySQL.

## 🌐 Demo
[Vedi il sito live](https://gymbook.altervista.org)

## ✨ Funzionalità
- Registrazione e login utenti con password cifrata
- Visualizzazione corsi con posti disponibili in tempo reale
- Prenotazione e cancellazione corsi
- Pannello amministratore per gestire corsi e prenotazioni
- Interfaccia responsive

## 🛠️ Tecnologie utilizzate
- PHP 8
- MySQL
- HTML5 / CSS3
- PDO per la gestione del database

## ⚙️ Installazione in locale
1. Clona il repository
   git clone https://github.com/tuonome/gymbook.git
2. Copia config/database.example.php in config/database.php
3. Inserisci le tue credenziali database in config/database.php
4. Importa il database con il file gymbook.sql
5. Avvia XAMPP e vai su localhost/gymbook

## 🗄️ Schema database
- users — gestione utenti e ruoli
- courses — corsi della palestra
- bookings — prenotazioni con stato confirmed/cancelled

## 👤 Autore
Il Tuo Nome — [LinkedIn](https://linkedin.com/in/tuonome)
