<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // opzionale, utile per sviluppo

require_once 'config.php';

try {
    $stmt = $conn->prepare("
        SELECT parola, vittorie
        FROM vclassificaparole
        ORDER BY vittorie DESC
        LIMIT 10
    ");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $output = [];
    foreach ($result as $row) {
        $output[] = [
            "parola" => $row["parola"],   // <- è l'ID
            "vittorie" => $row["vittorie"]
        ];
    }

    echo json_encode($output);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "Errore DB: " . $e->getMessage()
    ]);
}

// ➤ Controlla che il comando non sia vuoto prima di eseguire
$comando = ""; // ← questa variabile probabilmente è vuota
if (!empty($comando)) {
    shell_exec($comando);
}
// oppure commentala/rimuovila se non ti serve
