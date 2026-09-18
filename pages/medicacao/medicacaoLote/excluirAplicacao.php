<?php
session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'aplicacao.php';

if(!isset($_POST['medicacaoLoteId']) || empty($_POST['medicacaoLoteId'])){
     echo '<div class="alert alert-danger">Dados insuficientes para a exclusão.</div>';
     echo '<a href="medicacaoLote.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Voltar</a>';
     exit;     
}

$aplicacaoId = intval($_POST['medicacaoLoteId']);
$aplicacaoLoteId = intval($_POST['loteId']);

if($aplicacaoId <= 0 || $aplicacaoLoteId <= 0){
    echo '<div class="alert alert-danger">IDs Inválidos</div>';
    exit;
}

try {
    $aplicacao = new Aplicacao();
    $aplicacao->setMedicacaoLoteId($aplicacaoId);
    $aplicacao->setLoteId($aplicacaoLoteId);

    $sql = "DELETE FROM medicacaoLote WHERE medicacaoLoteId = :medicacaoLoteId";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':medicacaoLoteId', $aplicacao->getMedicacaoLoteId(), PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['loteIdAtual'] = $aplicacao->getLoteId();
    
    header("Location: medicacaoLote.php");
    exit;

} catch (PDOException $e) {
    echo "Erro ao excluir: " . $e->getMessage();
}
?>