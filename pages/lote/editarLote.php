<?php
    session_start();

    if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
    }

    require '../../config/conexao.php';
    require 'lote.php';

    if(
        !isset($_POST['loteId']) ||
        !isset($_POST['loteCodigo']) ||
        !isset($_POST['loteObs']) ||
        !isset($_POST['loteCriacao']) ||
        !isset($_POST['propriedadeId'])
    ){

        echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h4>Erro</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Dados incompletos. Certifique-se de que todos os campos foram preenchidos.
                </div>
                <a href="lotes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';

        exit;
    }

    $lote = new Lote();

    $lote->setId($_POST['loteId']);
    $lote->setCodigo($_POST['loteCodigo']);
    $lote->setObservacoes($_POST['loteObs']);
    $lote->setCriacao($_POST['loteCriacao']);
    $lote->setPropriedadeId($_POST['propriedadeId']);

    try{
        $sql = "UPDATE lote SET
        loteCodigo = :loteCodigo,
        loteObservacoes = :loteObs,
        loteCriacao = :loteCriacao
        WHERE loteId = :loteId";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':loteCodigo', $lote->getCodigo());
        $stmt->bindValue(':loteObs', $lote->getObservacoes());
        $stmt->bindValue(':loteCriacao',$lote->getCriacao());
        
        $stmt->bindValue(':loteId', $lote->getId(), PDO::PARAM_INT);

        $stmt->execute();

        $_SESSION['propriedadeIdAtual'] = $lote->getPropriedadeId();

        header("Location: lotes.php");
        exit;
        
    } catch(PDOException $e) {

        echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h4>Erro ao Salvar</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Erro ao atualizar: ' . $e->getMessage() . '
                </div>
                <a href="lotes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';    
    }
?>