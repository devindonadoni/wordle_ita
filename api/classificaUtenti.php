<?php
header('Content-Type: application/json');

// Include il file di configurazione per la connessione al DB
require_once 'config.php';

try {
    // Query alla vista
    $stmt = $conn->prepare("SELECT utente, vittorie FROM vclassificautenti LIMIT 10");
    $stmt->execute();

    // Ottieni i risultati come array associativi
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Ritorna i dati in JSON
    echo json_encode($result);
} catch (PDOException $e) {
    // In caso di errore, ritorna un JSON con errore
    echo json_encode([
        "success" => false,
        "error" => "Errore DB: " . $e->getMessage()
    ]);
}
