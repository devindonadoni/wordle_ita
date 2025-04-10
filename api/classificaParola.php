<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // opzionale, utile per sviluppo

require_once 'config.php';

try {
    // JOIN tra vista e tabella tParola per ottenere il testo della parola
    $stmt = $conn->prepare("
        SELECT tp.Parola AS parola, vp.vittorie
        FROM vclassificaParole vp
        JOIN tParola tp ON vp.parola = tp.idParola
        ORDER BY vp.vittorie DESC
        LIMIT 10
    ");
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "error" => "Errore DB: " . $e->getMessage()
    ]);
}
