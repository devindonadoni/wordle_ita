<?php
require_once('config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stringaInput = isset($_GET['stringa']) ? $_GET['stringa'] : '';

    if ($id > 0 && !empty($stringaInput)) {
        try {
            $stmt = $conn->prepare("CALL testParola(?, ?)");
            $stmt->execute([$id, $stringaInput]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode(["success" => true, "result" => $result["SequenzaRisultati"]]);
            } else {
                echo json_encode(["success" => false, "error" => "Nessun risultato trovato"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "error" => "Database error: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Parametri non validi"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metodo non supportato"]);
}
?>