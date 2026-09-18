<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'saida.php';

if(
    !isset($_POST['saidaLoteId']) ||
    !isset($_POST['saidaNf']) ||
    !isset($_POST['saidaGta']) ||
    !isset($_POST['saidaQuantidade']) ||
    !isset($_POST['saidaPm']) ||
    !isset($_POST['saidaDestino'])
){
    echo '<div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4>Erro</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">Dados incompletos</div>
                <a href="formulario_lote.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';

    exit;
}

$saida = new Saida();
    $saida->setLoteId($_POST['saidaLoteId']);
    $saida->setData($_POST['saidaData']);
    $saida->setNf($_POST['saidaNf']);
    $saida->setGta($_POST['saidaGta']);
    $saida->setQuantidade($_POST['saidaQuantidade']);
    $saida->setPm($_POST['saidaPm']);
    $saida->setDestino($_POST['saidaDestino']);

try{
        $sql = "INSERT INTO saida
        (saidaLoteId, saidaData, saidaNf, saidaGta, saidaQuantidade, saidaPm, saidaDestino)
        VALUES
        (:saidaLoteId, :saidaData, :saidaNf, :saidaGta, :saidaQuantidade, :saidaPm, :saidaDestino)";

        $stm = $conn->prepare($sql);

        $stm->bindValue(':saidaLoteId',$saida->getLoteId());
        $stm->bindValue(':saidaData',$saida->getData());
        $stm->bindValue(':saidaNf',$saida->getNf());
        $stm->bindValue(':saidaGta',$saida->getGta());
        $stm->bindValue(':saidaQuantidade',$saida->getQuantidade());
        $stm->bindValue(':saidaPm',$saida->getPm());
        $stm->bindValue(':saidaDestino',$saida->getDestino());

        $stm->execute();
        $_SESSION['loteIdAtual'] = $saida->getLoteId();
        header("Location: ../animais.php");
}catch(PDOException $e){


    echo '<div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4>Erro ao Salvar</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Erro ao inserir: ' . $e->getMessage() . '
                </div>
                <a href="formulario.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';    
}

?>