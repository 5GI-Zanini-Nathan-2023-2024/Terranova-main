<!DOCTYPE html>

<html>

    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>Operazioni CRUD - LOGIN</title>
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity = "sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin = "anonymous" referrerpolicy = "no-referrer">
    </head>

    <body>

        <script src = "javascript.js"></script>

        <article>

            <h1>SQL CRUD Operations - Login</h1>

            <div id = "loginContainer">

                <h2>ACCEDI ALLA TUA AREA RISERVATA</h2>

                <form id = "formLoginUtente" class = "formPersonalizzato" action = "login.php" method = "POST">

                    <div class = "gruppoInput">

                        <label for = "inputEmailUtente"> Email: </label>
                        <br>
                        <div class = "contenitoreInputIcona">
                            <i class = "fa fa-envelope inputIcon"> </i>
                            <input type = "email" id = "inputEmailUtente" name = "inputEmailUtente" pattern = "[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title = "Formato email valido: user@mail.com" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Email" required>
                            <i class = "fa fa-check" style = "margin-left: 60px"> </i>
                        </div>

                    </div>

                    <br>

                    <div class = "gruppoInput">

                        <label for = "inputPasswordUtente"> Password: </label>
                        <br>
                        <div class = "contenitoreInputIcona">
                            <i class = "fa fa-key inputIcon"> </i>
                            <input type = "password" id = "inputPasswordUtente" name = "inputPasswordUtente" pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title = "La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola ed un numero" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Password" required>
                            <i class = "fa fa-check" style = "margin-left: 60px"> </i>
                            <i class = "far fa-eye" id = "toggleUserPassword" style = "margin-left: -70px; cursor: pointer" onclick = "togglePasswordVisibility()"> </i>
                        </div>
                        <p id = "avvertimentoPasswordUtente" class = "avvertimenti"> </p>
                                
                    </div>

                    <br>

                    <button type = "submit" id = "bottoneRegistrazioneUtente" name = "bottoneRegistrazioneUtente" class = "bottoniInvioForm">LOGIN</button>

                </form>

                <?php

                    include "connection.php";       // Importazione del file "connection.php" utile per permettere la connessione al database.
                    session_start();                // Apertura della sessione PHP.

                    // Le istruzioni contenute all'interno di queste parentesi graffe verranno eseguite solamente se la variabile superglobale "$_POST" non è vuota ed è stata settata, ovvero se l'utente ha premuto il pulsante "LOGIN", inviando così il form:
                    if (isset($_POST) && !empty($_POST)) {

                        // Estrapolazione delle stringhe inserite dall'utente nei campi input per l'inserimento dell'email e della password:
                        $userEmail = $_POST["inputEmailUtente"];
                        $userPassword = $_POST["inputPasswordUtente"];

                        // Caso in cui la stringa inserita dall'utente nel campo input riservato all'email e/o in quello riservato alla password sia vuota:
                        if (empty($userEmail) || empty($userPassword)) {
                            echo '<br> <p class = "avvertimenti"> Attenzione: Assicurati di aver inserito le tue credenziali!</p>';         // Creazione di un paragrafo di avvertimento utile per invitare l'utente ad inserire le proprie credenziali negli appositi campi input del form.
                        }

                        // Caso in cui le stringhe inserite dall'utente nei campi input riservati all'email e alla password non siano vuote:
                        else {

                            $statement = $connection->prepare("SELECT Email, Password FROM Utenti WHERE Email = ?");        // Preparazione della query SELECT che dovrà essere effettuata sul database:
                            $statement->bind_param("s", $userEmail);                                                        // Bind dei parametri per prevenire attacchi di tipo SQL Injection.
                            $statement->execute();                                                                          // Esecuzione vera e propria della query.
                            $statement->store_result();                                                                     // Salvataggio del risultato della query.

                            // Caso in cui la query abbia restituito almeno 1 riga (ovvero l'email inserita dall'utente sia stata trovata all'interno del database):
                            if ($statement->num_rows > 0) {

                                $statement->bind_result($databaseEmail, $databasePassword);             // Preparazione delle variabili "$databaseEmail" e "$databasePassword" per la successiva memorizzazione dei risultati ottenuti dall'esecuzione della query sul database (ovvero email e password).
                                $statement->fetch();                                                    // Estrazione dei risultati della query eseguita e memorizzazione di essi all'interno delle variabili opportunamente preparate.
                                $passwordHash = password_hash($databasePassword, PASSWORD_DEFAULT);     // Assegnazione dell'hash generato dalla password memorizzata nel database alla variabile "$passwordHash".
                                
                                // Controllo della corrispondenza della password inserita dall'utente con l'hash della password memorizzata nel database:
                                if (password_verify($userPassword, $passwordHash)) {
                                    // Il login è avvenuto correttamente: vengono settate alcune variabili di sessione e l'utente viene reindirizzato alla propria area personale (pagina "dashboard.php").
                                    echo "<p class = 'conferme' style = 'margin-top: 400px'> Perfetto: l'accesso è avvenuto correttamente! </p>";       // Creazione di un paragrafo di conferma utile per informare l'utente che l'accesso è avvenuto correttamente.
                                    $_SESSION['loggedin'] = true;                       // Impostazione della variabile di sessione "loggedin".
                                    $_SESSION['session_id'] = session_id();             // Impostazione della variabile di sessione "session_id".
                                    $_SESSION['session_user_email'] = $userEmail;       // Impostazione della variabile di sessione "session_user_email".
                                    header('Location: personalArea.php');               // Reindirizzamento alla pagina "dashboard.php".
                                    exit;                                               // Terminazione delle istruzioni.
                                }

                                // Caso in cui la password inserita dall'utente non corrisponde all'hash memorizzato nel database:
                                else {
                                    // La registrazione viene negata e viene mostrato un messaggio di errore:
                                    echo '<p class = "avvertimenti" font-size: 45px; style = "margin-top: 25px"> Attenzione: Le credenziali inserite sono errate!</p>';         // Creazione di un paragrafo di avvertimento utile per informare l'utente che le credenziali inserite sono errate.
                                }
                            }

                            // Caso in cui la query non abbia restituito alcuna riga (ovvero l'email inserita dall'utente non sia stata trovata all'interno del database):
                            else {
                                // La registrazione viene negata e vengono mostrati un messaggio di errore ed un link alla pagina di registrazione:
                                $html_out = '<p class = "avvertimenti" style = "font-size: 25px; padding-top: 10px"> Attenzione: Non sei ancora registrato a questo sito!</p><br><br><br>';     // Creazione di un paragrafo di avvertimento utile per informare l'utente della mancata registrazione.
                                $html_out .= '<a href = "./registration.php" style = "font-size: 25px">Registrati</a>';                                                                         // Creazione di un link alla pagina di registrazione ("registration.php).
                                echo $html_out;                                                                                                                                                 // Inserimento nella pagina degli elementi html opportunamente preparati e formattati.
                            }
                        }

                        $statement->close();        // Chiusura e deallocazione dello statement utilizzato.
                    }

                ?>

            </div>

        </article>

        <footer>

        </footer>

    </body>

</html>