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
        !isset($_POST['racaoId']) ||
        !isset($_POST['racaoData']) ||
        !isset($_POST['racaoQuantidade']) ||
        !isset($_POST['racaoNf']) ||
        !isset($_POST['racaoTipo'])
    ){

        echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h4>Erro</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Dados incompletos. Certifique-se de que todos os campos foram preenchidos.
                </div>
                <a href="../animais.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';

        exit;
    }

    $racao = new Racao();

    $racao->setId($_POST['racaoId']);
    $racao->setLoteId($_POST['racaoLoteId']);
    $racao->setData($_POST['racaoData']);
    $racao->setQuantidade($_POST['racaoQuantidade']);
    $racao->setNf($_POST['racaoNf']);
    $racao->setTipo($_POST['racaoTipo']);

    try{
        $sql = "UPDATE racao SET
        racaoQuantidade = :racaoQuantidade,
        racaoNf = :racaoNf,
        racaoData = :racaoData,
        racaoTipo = :racaoTipo
        WHERE racaoId = :racaoId";

        $stm = $conn->prepare($sql);

        $stm->bindValue(':racaoId',$racao->getId());
        $stm->bindValue(':racaoTipo',$racao->getTipo());
        $stm->bindValue(':racaoQuantidade',$racao->getQuantidade());
        $stm->bindValue(':racaoNf',$racao->getNf());
        $stm->bindValue(':racaoData',$racao->getData());

        $stm->execute();
        $_SESSION['loteIdAtual'] = $racao->getLoteId();
        header("Location: racoes.php");
        
    } catch(PDOException $e) {

        echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h4>Erro ao Salvar</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Erro ao atualizar: ' . $e->getMessage() . '
                </div>
                <a href="../animais.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';    
    }
?>