<?php
require_once('config.php');

header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $word = $_POST['text'] ?? '';
    try {
    $stmt = $conn->prepare("CALL testVocabolario(:word)");
    $stmt->bindParam(':word', $word, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode(['exists' => ($result['risultato'] == 1)]);
    
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
}else{
    echo json_encode(["success" => false, "message" => "Metodo non supportato"]);
}

$conn = null;
?>