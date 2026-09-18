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
        !isset($_POST['morteId']) ||
        !isset($_POST['morteData']) ||
        !isset($_POST['mortePm']) ||
        !isset($_POST['morteQuantidade']) ||
        !isset($_POST['morteCausa']) 
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

    $morte = new Morte();

    $morte->setId($_POST['morteId']);
    $morte->setLoteId(($_POST['morteLoteId']));
    $morte->setData($_POST['morteData']);
    $morte->setPm($_POST['mortePm']); 
    $morte->setCausa($_POST['morteCausa']);
    $morte->setQuantidade($_POST['morteQuantidade']);

    try{
        $sql = "UPDATE morte SET
        morteQuantidade = :morteQuantidade,
        mortePm = :mortePm,
        morteData = :morteData,
        morteCausa = :morteCausa
        WHERE morteId = :morteId";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':morteData', $morte->getData());
        $stmt->bindValue(':mortePm', $morte->getPm());
        $stmt->bindValue(':morteQuantidade',$morte->getQuantidade());
        $stmt->bindValue(':morteCausa',$morte->getCausa());
        $stmt->bindValue(':morteId', $morte->getId(), PDO::PARAM_INT);

        $stmt->execute();
        
        $_SESSION['loteIdAtual'] = $morte->getLoteId();
        
        header("Location: ../animais.php");
        exit();
        
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