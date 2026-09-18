<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'entrada.php';

if(
    !isset($_POST['entradaLoteId']) ||
    !isset($_POST['entradaData']) ||
    !isset($_POST['entradaNf']) ||
    !isset($_POST['entradaOrigem']) ||
    !isset($_POST['entradaMachos']) ||
    !isset($_POST['entradaFemeas']) ||
    !isset($_POST['entradaPm']) ||
    !isset($_POST['entradaPt'])
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

$entrada = new Entrada();
    $entrada->setLoteId($_POST['entradaLoteId']);
    $entrada->setOrigem($_POST['entradaOrigem']);
    $entrada->setNf($_POST['entradaNf']);
    $entrada->setMachos($_POST['entradaMachos']);
    $entrada->setFemeas($_POST['entradaFemeas']);
    $entrada->setPm($_POST['entradaPm']);
    $entrada->setPt($_POST['entradaPt']);
    $entrada->setData($_POST['entradaData']);

try{
        $sql = "INSERT INTO entrada
        (entradaLoteId,entradaOrigem,entradaNf,entradaQuantidadeMachos,
        entradaQuantidadeFemeas,entradaPm,entradaPt,entradaData)
        VALUES
        (:entradaLoteId,:entradaOrigem,:entradaNf,:entradaMachos,:entradaFemeas,
        :entradaPm,:entradaPt,:entradaData)";

        $stm = $conn->prepare($sql);

        $stm->bindValue(':entradaLoteId',$entrada->getLoteId());
        $stm->bindValue(':entradaOrigem',$entrada->getOrigem());
        $stm->bindValue(':entradaNf',$entrada->getNf());
        $stm->bindValue(':entradaMachos',$entrada->getMachos());
        $stm->bindValue(':entradaFemeas',$entrada->getFemeas());
        $stm->bindValue(':entradaPm',$entrada->getPm());
        $stm->bindValue(':entradaPt',$entrada->getPt());
        $stm->bindValue(':entradaData',$entrada->getData());

        $stm->execute();
        $_SESSION['loteIdAtual'] = $entrada->getLoteId();
        header("Location: ../animais.php");
        exit();
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