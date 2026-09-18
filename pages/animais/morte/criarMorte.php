<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'morte.php';

if(
    !isset($_POST['morteLoteId']) ||
    !isset($_POST['morteData']) ||
    !isset($_POST['morteQuantidade']) ||
    !isset($_POST['mortePm']) ||
    !isset($_POST['morteCausa'])
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

$morte = new Morte();
    $morte->setLoteId($_POST['morteLoteId']);
    $morte->setData($_POST['morteData']);
    $morte->setQuantidade($_POST['morteQuantidade']);
    $morte->setPm($_POST['mortePm']);
    $morte->setCausa($_POST['morteCausa']);

try{
        $sql = "INSERT INTO morte
        (morteLoteId,morteData,morteQuantidade,mortePm,morteCausa)
        VALUES
        (:morteLoteId,:morteData,:morteQuantidade,:mortePm,:morteCausa)";

        $stm = $conn->prepare($sql);

        $stm->bindValue(':morteLoteId',$morte->getLoteId());
        $stm->bindValue(':morteCausa',$morte->getCausa());
        $stm->bindValue(':morteQuantidade',$morte->getQuantidade());
        $stm->bindValue(':mortePm',$morte->getPm());
        $stm->bindValue(':morteData',$morte->getData());

        $stm->execute();
        $_SESSION['loteIdAtual'] = $morte->getLoteId();
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