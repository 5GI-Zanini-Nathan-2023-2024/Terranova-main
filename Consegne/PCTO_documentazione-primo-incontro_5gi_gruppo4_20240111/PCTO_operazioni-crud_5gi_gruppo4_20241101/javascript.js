// Funzione necessaria per controllare se la password inserita dall'utente nel campo input identificato dall'ID "inputConfermaPasswordUtente" corrisponde a quella inserita nel campo input identificato dall'ID "inputPasswordUtente", e in caso negativo, mostrare un opportuno messaggio di avvertimento:
function checkPasswordsEquality () {

    let password = document.getElementById("inputPasswordUtente").value
    let confirmPassword = document.getElementById("inputConfermaPasswordUtente").value;
    let callerInputField = event.target;
    let callerInputFieldID = callerInputField.id;

    let warningText = "Attenzione! Le due password devono corrispondere!";

    if (callerInputFieldID == "inputConfermaPasswordUtente") {
        if (password.length > 0 && confirmPassword != password) {
            document.getElementById("avvertimentoConfermaPasswordUtente").innerHTML = warningText;
        }
        else {
            document.getElementById("avvertimentoConfermaPasswordUtente").innerHTML = "";
        }
    }

    else if (callerInputFieldID == "inputPasswordUtente") {
        if (confirmPassword.length > 0 && password != confirmPassword) {
            document.getElementById("avvertimentoPasswordUtente").innerHTML = warningText;
        }
        else {
            document.getElementById("avvertimentoPasswordUtente").innerHTML = "";
        }
    }
}

// Funzione necessaria per attivare/disattivare la visibilità della password inserita nell'apposito campo input:
function togglePasswordVisibility () {

    var callerID = event.target.id;
    var password;

    if (callerID == "toggleUserPassword") {
        password = document.getElementById("inputPasswordUtente");
    }
    else if (callerID == "toggleUserConfirmPassword") {
        password = document.getElementById("inputConfermaPasswordUtente");
    }
    else if (callerID == "toggleLoanUserPassword") {
        password = document.getElementById("inputPasswordUtentePrestito");
    }

    var parentDiv = password.parentNode;
    var currentEyeIcon = parentDiv.querySelector(':nth-child(4)');

    if (password.type == "password") {

        password.type = "text";

        currentEyeIcon.classList.remove("fa-eye");
        currentEyeIcon.classList.add("fa-eye-slash");
    }

    else if (password.type == "text") {

        password.type = "password";

        currentEyeIcon.classList.remove("fa-eye-slash");
        currentEyeIcon.classList.add("fa-eye");
    }
}

// Funzione richiamata dall'evento "onchange" e necessaria per modificare lo stile dei campi input nel caso in cui le informazioni inserite al loro interno siano errate:
function checkInputFieldValidity () {

    var callerInputField = event.target;
    var parentDiv = callerInputField.parentNode;
    var icon = parentDiv.getElementsByClassName("fa")[1];

    if (callerInputField.checkValidity()) {
        if (parentDiv.classList.contains("errore")) {
            parentDiv.classList.remove("errore");
        }
        if (icon.classList.contains("fa-times")) {
            icon.classList.remove("fa-times");
            icon.classList.add("fa-check");
        }
    }

    else {
        if (!(parentDiv.classList.contains("errore"))) {
            parentDiv.classList.add("errore");
        }
        if (icon.classList.contains("fa-check")) {
            icon.classList.remove("fa-check");
            icon.classList.add("fa-times");
        }
    }
}

// Funzione richiamata dall'evento "oninvalid" e necessaria per modificare lo stile dei campi input nel caso in cui le informazioni inserite al loro interno siano errate:
function styleInputFieldError () {

    var callerInputField = event.target;
    var parentDiv = callerInputField.parentNode;
    var icon = parentDiv.getElementsByClassName("fa")[1];

    if (!(parentDiv.classList.contains("errore"))) {
        parentDiv.classList.add("errore");
    }
    if (icon.classList.contains("fa-check")) {
        icon.classList.remove("fa-check");
        icon.classList.add("fa-times");
    }
}

// Funzione necessaria per ailitare la visualizzazione del form necessario per permettere all'utente di aggiornare le informazioni associate al proprio account:
function toggleFormVisibility () {
    let hiddenDiv = document.getElementById("hiddenDiv");
    hiddenDiv.setAttribute("style", "display: block");
}