<?php 

header('Content-Type: application/json');
session_start();

if(!isset($_SESSION['produtorId'])){
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso negado.']);
    exit;
}

require("../../config/conexao.php");

$nome = $_POST['editNome'] ?? '';
$tipo = $_POST['editTipo'] ?? '';
$id = $_POST['editId'] ?? '';

if(!empty($id) && !empty($tipo) && !empty($nome)){
    try{
        $sql = "UPDATE medicacao SET
        medicacaoNome = :medicacaoNome,
        medicacaoTipo = :medicacaoTipo
        WHERE medicacaoId = :medicacaoId";
        $stm = $conn->prepare($sql);
        $stm->bindValue(':medicacaoNome',$nome);
        $stm->bindValue('medicacaoTipo',$tipo);
        $stm->bindValue('medicacaoId', $id);
        $stm->execute();

        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Medicação editada com sucesso!']);
    } catch (PDOException $e){
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao salvar no banco: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Preencha todos os campos obrigatórios.']);
}
?>