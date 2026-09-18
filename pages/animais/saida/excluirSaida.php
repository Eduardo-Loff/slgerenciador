<?php

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'saida.php';

if(!isset($_POST['saidaId']) || empty($_POST['saidaId'])){
     echo '<div class="alert alert-danger">
            ID do lote não informado
          </div>';

    echo '<a href="lotes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>';

    exit;     
}

$saidaId = intval($_POST['saidaId']);
$saidaLoteId = intval($_POST['saidaLoteId']);

if($saidaId <= 0){
    echo '<div class="alert alert-danger">ID Inválido</div>';
    exit;
}

try{
    $saida = new Saida();
    $saida->setId($saidaId);
    $saida->setLoteId($saidaLoteId);


    $sql = "DELETE FROM saida WHERE saidaId = :saidaId";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':saidaId', $saida->getId(), PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['loteIdAtual'] = $saida->getLoteId();
    header("Location: ../animais.php");
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