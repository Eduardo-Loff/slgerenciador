<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../config/conexao.php';
require 'racao.php';

if(
    !isset($_POST['racaoLoteId']) ||
    !isset($_POST['racaoData']) ||
    !isset($_POST['racaoQuantidade']) ||
    !isset($_POST['racaoNf']) ||
    !isset($_POST['racaoTipo'])
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

$racao = new Racao();
    $racao->setLoteId($_POST['racaoLoteId']);
    $racao->setData($_POST['racaoData']);
    $racao->setQuantidade($_POST['racaoQuantidade']);
    $racao->setNf($_POST['racaoNf']);
    $racao->setTipo($_POST['racaoTipo']);

try{
        $sql = "INSERT INTO racao
        (racaoLoteId,racaoData,racaoQuantidade,racaoNf,racaoTipo)
        VALUES
        (:racaoLoteId,:racaoData,:racaoQuantidade,:racaoNf,:racaoTipo)";

        $stm = $conn->prepare($sql);

        $stm->bindValue(':racaoLoteId',$racao->getLoteId());
        $stm->bindValue(':racaoTipo',$racao->getTipo());
        $stm->bindValue(':racaoQuantidade',$racao->getQuantidade());
        $stm->bindValue(':racaoNf',$racao->getNf());
        $stm->bindValue(':racaoData',$racao->getData());

        $stm->execute();
        $_SESSION['loteIdAtual'] = $racao->getLoteId();
        header("Location: racoes.php");
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