<?php
require_once('config.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['idParola']) ? (int)$_POST['idParola'] : 0;
    $stringaInput = isset($_POST['stringa']) ? trim($_POST['stringa']) : '';

    // Validazione parametri
    if ($id > 0 && !empty($stringaInput) && strlen($stringaInput) <= 2) {
        try {
            // Chiamata alla stored procedure
            $stmt = $conn->prepare("CALL insertVittoria(:iniziali, :idparola)");
            $stmt->bindParam(':iniziali', $stringaInput, PDO::PARAM_STR);
            $stmt->bindParam(':idparola', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Verifica se ha inserito almeno una riga
            if ($stmt->rowCount() > 0) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Nessuna riga inserita"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["success" => false, "error" => "Errore DB: " . $e->getMessage()]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Parametri non validi o mancanti"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metodo non supportato"]);
}
?>
