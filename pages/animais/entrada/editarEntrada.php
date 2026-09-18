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
        !isset($_POST['entradaId']) ||
        !isset($_POST['entradaData']) ||
        !isset($_POST['entradaNf']) ||
        !isset($_POST['entradaOrigem']) ||
        !isset($_POST['entradaMachos']) ||
        !isset($_POST['entradaFemeas']) ||
        !isset($_POST['entradaPm']) ||
        !isset($_POST['entradaPt']) 
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

    $entrada = new Entrada();

    $entrada->setId($_POST['entradaId']);
    $entrada->setLoteId(($_POST['entradaLoteId']));
    $entrada->setData($_POST['entradaData']);
    $entrada->setNf($_POST['entradaNf']); 
    $entrada->setOrigem($_POST['entradaOrigem']);
    $entrada->setMachos($_POST['entradaMachos']);
    $entrada->setFemeas($_POST['entradaFemeas']);
    $entrada->setPm($_POST['entradaPm']);
    $entrada->setPt($_POST['entradaPt']);

    try{
        $sql = "UPDATE entrada SET
        entradaData = :entradaData,
        entradaNf = :entradaNf,
        entradaOrigem = :entradaOrigem,
        entradaQuantidadeMachos = :entradaMachos,
        entradaQuantidadeFemeas = :entradaFemeas,
        entradaPm = :entradaPm,
        entradaPt = :entradaPt
        WHERE entradaId = :entradaId";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':entradaData', $entrada->getData());
        $stmt->bindValue(':entradaNf', $entrada->getNf());
        $stmt->bindValue(':entradaOrigem',$entrada->getOrigem());
        $stmt->bindValue(':entradaMachos',$entrada->getMachos());
        $stmt->bindValue(':entradaFemeas',$entrada->getFemeas());
        $stmt->bindValue(':entradaPm',$entrada->getPm());
        $stmt->bindValue(':entradaPt',$entrada->getPt());
        $stmt->bindValue(':entradaId', $entrada->getId(), PDO::PARAM_INT);

        $stmt->execute();
        
        $_SESSION['loteIdAtual'] = $entrada->getLoteId();
        
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