<!DOCTYPE html>

<html>

    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
        <title>Operazioni CRUD - AREA PERSONALE (READ, UPDATE AND DELETE)</title>
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <link rel = "stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity = "sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin = "anonymous">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity = "sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin = "anonymous" referrerpolicy = "no-referrer">

    </head>

    <body>

        <script src = "javascript.js"></script>

        <article>

            <?php

                include "connection.php";       // Importazione del file "connection.php" utile per permettere la connessione al database.
                session_start();                // Apertura della sessione PHP.

                // Controllo di sessione: le istruzioni contenute all'interno di queste parentesi graffe verranno eseguite solamente nel caso in cui la variabile di sessione "loggedin" sia stata settata e contenga il valore booleano true:
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

                    $statement = $connection->prepare("SELECT Email, Nome, Cognome, Telefono, Citta, Via, Nascita FROM Utenti WHERE Email = ?");
                    $statement->bind_param("s", $_SESSION["session_user_email"]);       // Bind del parametro (ovvero l'indirizzo email dell'utente, recuperato dalla variabile di sessione "session_user_login" settata al momento del login dell'utente) per evitare attacchi di tipo "SQL Injection".
                    $statement->execute();              // Esecuzione vera e propria della query.
                    $statement->store_result();         // Salvataggio del risultato ottenuto dall'esecuzione della query.
                    $statement->bind_result($userEmail, $userName, $userSurname, $userTelephone, $userCity, $userStreet, $userBirthDate);       // Bind dei parametri (ovvero indirizzo email, nome, cognome, numero di telefono, città di nascita, via di residenza e data di nascita dell'utente che ha effettuato il login) per evitare attacchi di tipo "SQL Injection".
                    $statement->fetch();                // Estrapolazione dei risultati e assegnazione di essi alle variabili appena specificate per il bind del risultato.
                    $numRows = mysqli_stmt_num_rows($statement);
                    $statement->close();                // Chiusura e deallocazione dello statement.
                
                    $html_out = '<br><br><p>Di seguito sono riportate le tue informazioni personali:</p><br>';

                    $html_out .= '<div id = "personalInformationContainer">';                   // Creazione di un blocco di classe "personalInformationContainer" (ovvero il blocco necessario per contenere le infromazioni personali dell'utente che verranno mostrate sulla pagina).

                    if ($numRows == 1) {
                        $html_out .= '<p><b>Nome:</b> ' . $userName . '</p>';                       // Creazione di un paragrafo di testo contenente il nome dell'utente.
                        $html_out .= '<p><b>Cognome:</b> ' . $userSurname . '</p>';                 // Creazione di un paragrafo di testo contenente il cognome dell'utente.
                        $html_out .= '<p><b>Email:</b> ' . $userEmail . '</p>';                     // Creazione di un paragrafo di testo contenente l'indirizzo email dell'utente.
                        $html_out .= '<p><b>Numero di telefono:</b> ' . $userTelephone . '</p>';    // Creazione di un paragrafo di testo contenente il numero di telefono dell'utente.
                        $html_out .= '<p><b>Città:</b> ' . $userCity . '</p>';                      // Creazione di un paragrafo di testo contenente la città di nascita dell'utente.
                        $html_out .= '<p><b>Via di residenza:</b> ' . $userStreet . '</p>';         // Creazione di un paragrafo di testo contenente la via di residenza dell'utente.
                        $html_out .= '<p><b>Data di nascita:</b> ' . $userBirthDate . '</p>';       // Creazione di un paragrafo di testo contenente la data di nascita dell'utente.
                    }

                    else if ($numRows == 0) {
                        $html_out .= '<p class = "conferme">Il tuo account è stato eliminato correttamente!</p>';
                    }

                    $html_out .= '</div>';      // Chiusura del blocco di classe "personalInformationContainer" (ovvero il blocco necessario per contenere le infromazioni personali dell'utente che verranno mostrate sulla pagina).

                    echo $html_out;             // Inserimento nella pagina del codice html opportnamente preparato e concatenato all'interon della stringa "html_out".
                }

                // Caso in cui l'utente non ha precedentemente eseguito l'accesso attraverso la pagina di login ("login.php").
                else {
                    header('Location: login.php');      // Reindirizzamento alla pagina di login ("login.php").
                }

            ?>

            <br> <br> <br>

            <p>Clicca qui per modificare le informazioni associate al tuo account:</p>
            <button type = "button" id = "bottoneAggiornamentoInformazioniAccount" name = "bottoneAggiornamentoInformazioniAccount" class = "btn btn-outline-warning btn-lg" onclick = "toggleFormVisibility()">Modifica il tuo account</button>

            <div id = "hiddenDiv" style = "display: none">
                
                <br> <br>
                <p>Attenzione: Compila tutti i campi con le tue informazioni, non solo quelli riguardanti le informazioni che desideri modificare!</p>

                <div id = "informationUpdateContainer">
                
                    <form id = "formAggiornamentoInformazioniUtente" class = "formPersonalizzato" method = "POST" action = "personalArea.php">

                        <div class = "gruppoInput" style = "position: absolute; top: 15%; right: 55%">

                            <label for = "inputNomeUtente"> Nome: </label>
                            
                            <div class = "contenitoreInputIcona">

                                <i class = "fa fa-user inputIcon"> </i>
                                <input type = "text" id = "inputNomeUtente" name = "inputNomeUtente" pattern = "[A-Z a-z]{1,20}" title = "Non sono ammessi numeri o caratteri speciali; Lunghezza massima: 20 caratteri" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Nome" required>
                                <i class="fa fa-check"></i>

                            </div>

                        </div>

                        <div class = "gruppoInput" style = "position: absolute; top: 15%; left: 55%">

                            <label for = "inputCognomeUtente"> Cognome: </label>
                            <div class = "contenitoreInputIcona">

                                <i class = "fa fa-user inputIcon"> </i>
                                <input type = "text" id = "inputCognomeUtente" name = "inputCognomeUtente" pattern = "[A-Z a-z]{1,20}" title = "Non sono ammessi numeri o caratteri speciali; Lunghezza massima: 20 caratteri" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Cognome" required>
                                <i class = "fa fa-check"></i>
                                
                            </div>

                        </div>

                        <br> <br> <br>

                        <div class = "gruppoInput" style = "position: absolute; top: 30%; left: 55%">

                            <label for = "inputEmailUtente"> Email: </label>
                            <div class = "contenitoreInputIcona">

                                <i class = "fa fa-envelope inputIcon"> </i>
                                <input type = "email" id = "inputEmailUtente" name = "inputEmailUtente" pattern = "[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title = "Formato email valido: user@mail.com" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Email" disabled>
                                <i class = "fa fa-check"> </i>

                            </div>

                        </div>

                        <div class = "gruppoInput" style = "position: absolute; top: 30%; right: 55%">

                            <label for = "inputNumeroTelefonoUtente"> Numero di telefono: </label>   
                            <div class = "contenitoreInputIcona">
                                <i class = "fa fa-phone inputIcon" style = "transform: rotate(180deg)"> </i>
                                <input type = "text" id = "inputNumeroTelefonoUtente" name = "inputNumeroTelefonoUtente" pattern = "[0-9]{10}" title = "Non sono ammesse lettere o caratteri speciali" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Numero di telefono" required>
                                <i class = "fa fa-check"> </i>
                            </div>

                        </div>

                        <br> <br> <br>

                        <div class = "gruppoInput" style = "position: absolute; top: 45%; right: 55%; width: 380px">

                            <label for = "inputPasswordUtente"> Password: </label>
                            <div class = "contenitoreInputIcona">
                                <i class = "fa fa-key inputIcon"> </i>
                                <input type = "password" id = "inputPasswordUtente" name = "inputPasswordUtente" pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title = "La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola ed un numero" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Password" oninput = "checkPasswordsEquality()" required>
                                <i class = "fa fa-check" style = "position: absolute; right: 9%"> </i>
                                <i class = "far fa-eye" id = "toggleUserPassword" style = "position: absolute; right: 6%; cursor: pointer" onclick = "togglePasswordVisibility()"> </i>
                            </div>
                            <p id = "avvertimentoPasswordUtente" class = "avvertimenti" style = "position: absolute; margin-top: 120px; margin-left: 10px"> </p>
                                        
                        </div>

                        <div class = "gruppoInput" style = "position: absolute; top: 45%; left: 55%; width: 380px">

                            <label for = "inputConfermaPasswordUtente"> Conferma password: </label>                   
                            <div class = "contenitoreInputIcona">
                                <i class = "fa fa-key inputIcon"> </i>    
                                <input type = "password" id = "inputConfermaPasswordUtente" name = "inputConfermaPasswordUtente" pattern = "(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title = "La password deve contenere almeno 8 caratteri, una lettera maiuscola, una minuscola ed un numero" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Conferma password" oninput = "checkPasswordsEquality()" required>
                                <i class = "fa fa-check" style = "position: absolute; right: 9%"> </i>
                                <i class = "far fa-eye" id = "toggleUserConfirmPassword" style = "position: absolute; right: 6%; cursor: pointer" onclick = "togglePasswordVisibility()"> </i>    
                            </div>
                            <p id = "avvertimentoConfermaPasswordUtente" class = "avvertimenti" style = "position: absolute; margin-top: 120px; margin-right: 10px"> </p>
                        </div>

                        <br>

                        <div class = "gruppoInput" style = "position: absolute; top: 72%; left: 55%">

                            <label for = "inputCittàNascitaUtente"> Città di nascita: </label>
                            <div class = "contenitoreInputIcona">

                                <i class = "fa fa-globe inputIcon"> </i>
                                <input type = "text" id = "inputCittàNascitaUtente" name = "inputCittàNascitaUtente" pattern = "[A-Z a-z]{1,30}" title = "Inserisci qui la tua città di nascita" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Città" required>
                                <i class = "fa fa-check"> </i>

                            </div>

                        </div>

                        <div class = "gruppoInput" style = "position: absolute; top: 72%; right: 55%">
                            <label for = "inputViaResidenzaUtente"> Via di residenza: </label>
                            <div class = "contenitoreInputIcona">
                                <i class = "fa fa-location-arrow inputIcon"> </i>
                                <input type = "text" id = "inputViaResidenzaUtente" name = "inputViaResidenzaUtente" pattern = "[A-Z a-z0-9/]{1,50}" title = "Inserisci qui la tua via di residenza" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" placeholder = "Residenza" required>
                                <i class = "fa fa-check"> </i>
                            </div>
                        </div>

                        <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>

                        <div class = "gruppoInput" style = "position: absolute; top: 89%; left: 36.5%">

                            <label for = "inputDataNascitaUtente"> Data di nascita: </label>
                            <div class = "contenitoreInputIcona">
                                <i class = "fa fa-calendar inputIcon"> </i>
                                <input type = "date" id = "inputDataNascitaUtente" name = "inputDataNascitaUtente" title = "Inserisci qui la tua data di nascita" oninvalid = "styleInputFieldError()" onchange = "checkInputFieldValidity()" required>
                                <i class = "fa fa-check"> </i>
                            </div>

                        </div>

                        <br> <br>

                        <button type = "submit" id = "bottoneConfermaAggiornamentoInformazioni" name = "bottoneConfermaAggiornamentoInformazioni" class = "bottoniInvioForm" style = "position: relative; top: 160px"> Aggiorna le informazioni </button>

                    </form>

                    <?php

                        if (isset($_POST) && !empty($_POST) && isset($_POST["bottoneConfermaAggiornamentoInformazioni"])) {

                            include "connection.php";       // Importazione del file "connection.php" utile per permettere la connessione al database.

                            // Dati inseriti dall'utente negli appositi campi input del form:
                            $nomeUtente = $_POST["inputNomeUtente"];                            // Nome dell'utente.
                            $cognomeUtente = $_POST["inputCognomeUtente"];                      // Cognome dell'utente.
                            $passwordUtente = $_POST["inputPasswordUtente"];                    // Password scelta dall'utente.
                            $numeroTelefonoUtente = $_POST["inputNumeroTelefonoUtente"];        // Conferma della password scelta.
                            $cittàNascitaUtente = $_POST["inputCittàNascitaUtente"];            // Città di nascita dell'utente.
                            $viaResidenzaUtente = $_POST["inputViaResidenzaUtente"];            // Via di residenza dell'utente.
                            $dataNascitaUtente = $_POST["inputDataNascitaUtente"];              // Data di nascita dell'utente.
        
                            $statement = $connection->prepare("UPDATE Utenti SET Email = ?, Password = ?, Nome = ?, Cognome = ?, Telefono = ?, Citta = ?, Via = ?, Nascita = ? WHERE Email = ?");                                       // Preparazione della query di aggiornamento da eseguire sul database (utile per modificare le infromazioni personali di un utente). 
                            $statement->bind_param("sssssssss", $userEmail, $passwordUtente, $nomeUtente, $cognomeUtente, $numeroTelefonoUtente, $cittàNascitaUtente, $viaResidenzaUtente, $dataNascitaUtente, $userEmail);             // Bind dei parametri (corrispondenti alle credenziali inserite dall'utente negli appositi campi input del form) per evitare attacchi di tipo "SQL Injection".
                            $statement->execute();      // Esecuzione vera e propria della query appositamente preparata.
                            
                            $html_out = '<script>';
                            $html_out .= 'let paragrafo = document.createElement("p");';
                            $html_out .= 'paragrafo.innerHTML = "Perfetto: Hai aggiornato correttamente le tue informazioni personali! Ricarica la pagina per vedere il risultato!";';
                            $html_out .= 'paragrafo.setAttribute("class", "conferme");';
                            $html_out .= 'paragrafo.setAttribute("style", "position: absolute; margin-top: 50px; margin-left: 350px");';
                            $html_out .= 'document.body.appendChild(paragrafo);';
                            $html_out .= '</script>';
                            
                            echo $html_out;             // Creazione di un paragrafo di conferma utile per informare l'utente che l'aggiornamento delle informazioni personali è avvenuto correttamente.
                        }
                    ?>

                    <br> <br> <br>

                </div>

            </div>

            <div style = "position: relative; margin-top: 30px">
                <form name = "formBottoneCancellazioneAccount" id = "formBottoneCancellazioneAccount" method = "POST" action = "personalArea.php">
                    <p>Clicca qui per rimuovere il tuo account:</p>
                    <button type = "submit" name = "bottoneRimozioneAccount" id = "bottoneRimozioneAccount" class = "btn btn-outline-danger btn-lg">Rimuovi il tuo account</button>
                </form>
            </div>

            <?php

                if (isset($_POST) && !empty($_POST) && isset($_POST["bottoneRimozioneAccount"])) {

                    $html_out =  '<script>';
                    $html_out .= 'document.getElementById("bottoneRimozioneAccount").setAttribute("disabled", "true");';
                    $html_out .= 'let container = document.getElementById("personalInformationContainer");';
                    $html_out .= 'while (container.firstChild) {';
                    $html_out .= 'container.removeChild(container.lastChild);';
                    $html_out .= '}';
                    $html_out .= 'let paragrafo = document.createElement("p");';
                    $html_out .= 'paragrafo.innerHTML = "Il tuo account è stato eliminato correttamente!";';
                    $html_out .= 'paragrafo.setAttribute("class", "conferme");';
                    $html_out .= 'container.appendChild(paragrafo);';
                    $html_out .= '</script>';

                    $query = "DELETE FROM Utenti WHERE Email = ?";
                    mysqli_execute_query($connection, $query, [$_SESSION["session_user_email"]]);

                    echo $html_out;
                }

            ?>

        </article>

        <br><br>

        <footer>

        </footer>

    </body>

</html>