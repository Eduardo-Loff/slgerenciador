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
        !isset($_POST['saidaId']) ||
        !isset($_POST['saidaData']) ||
        !isset($_POST['saidaNf']) ||
        !isset($_POST['saidaGta']) ||
        !isset($_POST['saidaQuantidade']) ||
        !isset($_POST['saidaPm']) ||
        !isset($_POST['saidaDestino']) 
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

    $saida = new Saida();

    $saida->setId($_POST['saidaId']);
    $saida->setLoteId(($_POST['saidaLoteId']));
    $saida->setData($_POST['saidaData']);
    $saida->setNf($_POST['saidaNf']); 
    $saida->setGta($_POST['saidaGta']);
    $saida->setQuantidade($_POST['saidaQuantidade']);
    $saida->setPm($_POST['saidaPm']);
    $saida->setDestino($_POST['saidaDestino']);

    try{
        $sql = "UPDATE saida SET
        saidaData = :saidaData,
        saidaNf = :saidaNf,
        saidaGta = :saidaGta,
        saidaQuantidade = :saidaQuantidade,
        saidaPm = :saidaPm,
        saidaDestino = :saidaDestino
        WHERE saidaId = :saidaId";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':saidaData', $saida->getData());
        $stmt->bindValue(':saidaNf', $saida->getNf());
        $stmt->bindValue(':saidaGta',$saida->getGta());
        $stmt->bindValue(':saidaQuantidade',$saida->getQuantidade());
        $stmt->bindValue(':saidaPm',$saida->getPm());
        $stmt->bindValue(':saidaDestino',$saida->getDestino()); 
        $stmt->bindValue(':saidaId', $saida->getId(), PDO::PARAM_INT);

        $stmt->execute();
        
        $_SESSION['loteIdAtual'] = $saida->getLoteId();
        
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