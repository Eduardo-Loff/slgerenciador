<?php
header('Content-Type: application/json');
session_start();

if(!isset($_SESSION['produtorId'])){
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso negado.']);
    exit;
}

require("../../config/conexao.php");

$nome = $_POST['medicacaoNome'] ?? '';
$tipo = $_POST['medicacaoTipo'] ?? '';

if (!empty($nome) && !empty($tipo)) {
    try {
        $sql = "INSERT INTO medicacao (medicacaoNome, medicacaoTipo) VALUES (:nome, :tipo)";
        $stm = $conn->prepare($sql);
        $stm->bindValue(':nome', $nome);
        $stm->bindValue(':tipo', $tipo);
        $stm->execute();

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Medicação cadastrada com sucesso!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos obrigatórios.']);
}

?>