<?php
require_once('api/config.php');
session_start();

$query = "CALL  getIdRandom()";
$stmt = $conn->prepare("CALL getIdRandom()");

// Esegui la procedura
$stmt->execute();

// Ottieni il risultato
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Salva l'ID ottenuto
$idParola = $result['idParola'];

$_SESSION['random_id'] = $idParola;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WORDLE</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/png" href="images/logo.png">
</head>

<body>
    <div class="centerword">
        <!-- Griglia principale -->
        <div class="grid-container">
            <h1 id="errorMessage" class="missing-word">parola inesistente</h1>
            <!-- Generazione dinamica della griglia -->
            <div class="grid-row">
                <input type="text" class="elementletter" id="C1R1" readonly value="">
                <input type="text" class="elementletter" id="C1R2" readonly value="">
                <input type="text" class="elementletter" id="C1R3" readonly value="">
                <input type="text" class="elementletter" id="C1R4" readonly value="">
                <input type="text" class="elementletter" id="C1R5" readonly value="">
            </div>
            <div class="grid-row">
                <input type="text" class="elementletter" id="C2R1" readonly value="">
                <input type="text" class="elementletter" id="C2R2" readonly value="">
                <input type="text" class="elementletter" id="C2R3" readonly value="">
                <input type="text" class="elementletter" id="C2R4" readonly value="">
                <input type="text" class="elementletter" id="C2R5" readonly value="">
            </div>
            <div class="grid-row">
                <input type="text" class="elementletter" id="C3R1" readonly value="">
                <input type="text" class="elementletter" id="C3R2" readonly value="">
                <input type="text" class="elementletter" id="C3R3" readonly value="">
                <input type="text" class="elementletter" id="C3R4" readonly value="">
                <input type="text" class="elementletter" id="C3R5" readonly value="">
            </div>
            <div class="grid-row">
                <input type="text" class="elementletter" id="C4R1" readonly value="">
                <input type="text" class="elementletter" id="C4R2" readonly value="">
                <input type="text" class="elementletter" id="C4R3" readonly value="">
                <input type="text" class="elementletter" id="C4R4" readonly value="">
                <input type="text" class="elementletter" id="C4R5" readonly value="">
            </div>
            <div class="grid-row">
                <input type="text" class="elementletter" id="C5R1" readonly value="">
                <input type="text" class="elementletter" id="C5R2" readonly value="">
                <input type="text" class="elementletter" id="C5R3" readonly value="">
                <input type="text" class="elementletter" id="C5R4" readonly value="">
                <input type="text" class="elementletter" id="C5R5" readonly value="">
            </div>
        </div>
        <!-- Campo input e pulsante submit -->
        <div class="input-container">
            <input type="submit" class="submitbutton" id="submit" value="SUBMIT" disabled onclick="submitWord()">
        </div>
    </div>

    <div id="winMessage" class="win-message">
        <div class="win-box">
            <h2>HAI VINTO!</h2>
            <input type="text" id="initials" placeholder="Inserisci qualcosa..." required />
            <div class="button-group">
                <button type="button" name="classifica" onclick="visualizzaClassifica()">Classifica</button>
                <button type="button" name="rigioca" onclick="reloadWinGame()">Salva & Rinizia</button>
            </div>
        </div>
    </div>

    <!-- <div id="winMessage" class="win-message">
        Hai vinto!
        <button onclick="reloadGame()">Rigioca</button>
    </div> -->

    <div id="gameOver" class="game-over">
        hai perso...
        <button onclick="reloadGame()">Riprova</button>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    let paroleItaliane = []; // Sarà popolato con le parole dal file word.txt
    let paroladelgiorno = ''; // La parola da indovinare
    let currentRow = 1;
    let tentativi = 0;
    let randomId;

    function visualizzaClassifica() {

    }

    function salvaVittoria() {
        let valore = document.getElementById("initials").value;
        if (valore) {
            console.log('salvataggio in corso');
            $.ajax({
                url: "api/vittoria.php",
                type: "POST",
                data: { idParola: randomId, stringa: valore },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        console.log(response.result);
                    } else {
                        $("#risultato").text("Errore: " + response.error);
                    }
                },
                error: function () {
                    $("#risultato").text("Errore nella chiamata AJAX.");
                }
            });
        } else {
            console.log("INSERISCI LETTERE PER SALVARE");
        }

    }

    function setRandomWord() {
        // const randomIndex = Math.floor(Math.random() * paroleItaliane.length);
        // paroladelgiorno = paroleItaliane[randomIndex];
        // console.log('Parola del giorno file:', paroladelgiorno);

        // randomId = <?php echo $_SESSION['random_id']; ?>;
        randomId = 6;
        console.log('parole del giorno php: ', randomId);
    }

    function reloadWinGame() {
        salvaVittoria();
        location.reload();
    }

    // Ricarica il gioco
    function reloadGame() {
        location.reload();
    }

    function getTextFromGrid() {
        let word = '';
        for (let i = 1; i <= 5; i++) {
            word += document.getElementById(`C${currentRow}R${i}`).value.toLowerCase();
        }
        return word;
    }

    function submitState() {
        const button = document.getElementById("submit");
        const word = getTextFromGrid();
        button.disabled = word.length !== 5;
        button.style.backgroundColor = word.length === 5 ? "#104503" : "#b30713";
    }

    function testword(text) {
        console.log('test in corso');
        return $.ajax({
            url: 'api/checkword.php',
            method: 'POST',
            dataType: 'json',
            data: { text: text }
        })
            .then(function (response) {
                return response.exists === true;
            })
            .catch(function (error) {
                console.error('Errore nella richiesta:', error.responseText || error);
                return false;
            });
    }

    async function submitWord() {
        const word = getTextFromGrid();

        try {
            const exists = await testword(word);

            if (exists) {
                console.log('controllo true');
                tentativi++;
                console.log('Tentativi effettuati:', tentativi);

                if (tentativi <= 5) {
                    $.ajax({
                        url: "api/word-correct.php",
                        type: "GET",
                        data: { id: randomId, stringa: word },
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                console.log(response.result);

                                if (response.result === "11111") {
                                    for (let i = 0; i < word.length; i++) {
                                        const inputElement = document.getElementById(`C${currentRow}R${i + 1}`);
                                        inputElement.value = word[i];
                                        inputElement.style.backgroundColor = "#104503"; // Verde
                                    }
                                    document.getElementById("winMessage").style.display = "flex";
                                } else {
                                    const verdeArray = response.result.split('').map(Number);
                                    for (let i = 0; i < word.length; i++) {
                                        const inputElement = document.getElementById(`C${currentRow}R${i + 1}`);
                                        inputElement.value = word[i];
                                        if (verdeArray[i] === 1) {
                                            inputElement.style.backgroundColor = "#104503"; // Verde
                                        }
                                    }
                                    testCaracter(word, verdeArray);
                                }
                            } else {
                                $("#risultato").text("Errore: " + response.error);
                            }
                        },
                        error: function () {
                            $("#risultato").text("Errore nella chiamata AJAX.");
                        }
                    });
                } else {
                    document.getElementById("gameOver").style.display = "flex";
                }
            } else {
                document.getElementById("errorMessage").style.display = "block";

                resetGrid();
            }

        } catch (error) {
            console.error("Errore nella verifica della parola:", error);
            document.getElementById("errorMessage").style.display = "block";
            resetGrid();
        }
    }

    // Funzione per resettare la griglia
    function resetGrid() {
        for (let i = 0; i < 5; i++) {
            const inputElement = document.getElementById(`C${currentRow}R${i + 1}`);
            inputElement.value = '';
            inputElement.style.backgroundColor = "";
        }
    }


    function testCaracter(word, verdeArray) {
        $.ajax({
            url: "api/caracter-correct.php",
            type: "GET",
            data: { id: randomId, stringa: word },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    console.log(response.result);
                    const arancioneArray = response.result.split('').map(Number);

                    for (let i = 0; i < word.length; i++) {
                        const inputElement = document.getElementById(`C${currentRow}R${i + 1}`);

                        if (verdeArray[i] !== 1) {
                            if (arancioneArray[i] === 1) {
                                inputElement.style.backgroundColor = "#FFA501"; // Arancione
                            } else {
                                inputElement.style.backgroundColor = "#454442"; // Grigio
                            }
                        }
                    }

                    let vittoria = verdeArray.every(val => val === 1);
                    if (vittoria) {
                        document.getElementById("winMessage").style.display = "flex";
                        return;
                    }

                    currentRow++;

                    if (tentativi > 5) {
                    }
                } else {
                    console.log('Errore nella chiamata');
                }
            }
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Evita che il form venga inviato
            submitWord();
        } else if (event.key.length === 1 && /^[a-zA-Z]$/.test(event.key)) {
            const word = getTextFromGrid();

            if (word.length < 5) {
                for (let i = 0; i < 5; i++) {
                    const inputElement = document.getElementById(`C${currentRow}R${i + 1}`);
                    if (!inputElement.value) {
                        inputElement.value = event.key.toUpperCase();  // Aggiungi la lettera alla griglia
                        break;
                    }
                }
            }
        } else if (event.key === 'Backspace') {
            const word = getTextFromGrid();

            if (word.length > 0) {
                for (let i = 4; i >= 0; i--) {
                    const inputElement = document.getElementById(`C${currentRow}R${i + 1}`);
                    if (inputElement.value) {
                        inputElement.value = '';
                        break;
                    }
                }
            }
        }

        submitState();
    });

    setRandomWord();

</script>

</html>