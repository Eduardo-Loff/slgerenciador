<?php

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../config/conexao.php';
require 'racao.php';

if(!isset($_POST['racaoId']) || empty($_POST['racaoId'])){
     echo '<div class="alert alert-danger">
            ID do lote não informado
          </div>';

    echo '<a href="lotes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>';

    exit;     
}

$racaoId = intval($_POST['racaoId']);
$racaoLoteId = intval($_POST['racaoLoteId']);

if($racaoId <= 0){
    echo '<div class="alert alert-danger">ID Inválido</div>';
    exit;
}

try{
    $racao = new Racao();
    $racao->setId($racaoId);
    $racao->setLoteId($racaoLoteId);


    $sql = "DELETE FROM racao WHERE racaoId = :racaoId";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':racaoId', $racao->getId(), PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['loteIdAtual'] = $racao->getLoteId();
    header("Location: racoes.php");
    exit;

} catch (PDOException $e) {

    echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h4>Erro</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Erro ao excluir: ' . $e->getMessage() . '
                </div>
                <a href="lotes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';
}
?>