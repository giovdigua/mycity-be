## Istruzioni operative e resoconto di sviluppo

Per il test ho usato Laravel 11 con Passport per l'autenticazione e una Policy sul Model User per l'autorizzazione.
Il progetto è stato sviluppato usando Laravel Sail per una completa dockerizzazione.
Al Sail (seguire eventuali istruzioni sul proprio os https://laravel.com/docs/11.x/installation#docker-installation-using-sail)
in fase di avvio ho chiesto un db MySql e il package MailPit per la gestione della posta in locale.Aggiunto poi sul docker compose il phpmyadmin comodo per la visone del db.

Come da traccia gli utenti si potranno registrare con tutti i campi richiesti e convalidati .
Una volta registrati partirà email di conferma.Vi è anche la possibilità di fare il resend della mail di conferma (completato da interfaccia su vue).
Una volta regsitrati e validata la mail l'utente potrà effettuare il login e trovare la lista degli utenti.Se l'utente amministratore potrà elimnare gli altri utenti (non gli altri amministratori ne se stesso) o modificare i campi : 
name,surname,fiscal_code,email,phone_number e date_of_birth.

Per far partire il progetto una volta scaricata la repo da terminale eseguire: 

```
./vendor/bin/sail up
```

Esguire le migrations:
```
./vendor/bin/sail artisan migrate
```

Esguire il seed per la creazione di 1000 utenti random
```
./vendor/bin/sail artisan db:seed
```
 Si può poi procedere poi alla registrazione di un utente che sarà il nostro utente admin da interfaccia Vue.
 
Una volta registrato l'utente validare la mail arrivata su Mailpit andando su:
http://localhost:8025

Poi cambiare il role dell'utente creato in admin da db usando phpmyadmin all'indirizzo:
http://localhost:8089/ (server:mysql,Nome Utente: sail, password:password);

Esguire la query per aggiornare il role utente sostituendo tuoindirizzo@mail.com con l'idirizzo mail registrato:

```
update users set role = 'admin' where email = 'tuoindirizzo@mail.com';
```

Per il job della creazione del csv aprire un terminale e digitare:

```
 ./vendor/bin/sail artisan queue:work
```
Poi sul browser andare all'indirizzo http://localhost/export

Per la mail risultato aprire Mailpit http://localhost:8025
