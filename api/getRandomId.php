<?php
require_once('config.php');
session_start();

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("CALL getIdRandom()");
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result || !isset($result['idParola'])) {
        echo json_encode(['success' => false, 'message' => 'Nessun ID restituito']);
        exit;
    }

    $idParola = $result['idParola'];
    $_SESSION['random_id'] = $idParola;

    echo json_encode([
        'success' => true,
        'randomId' => $idParola
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Errore DB: ' . $e->getMessage()
    ]);
}
