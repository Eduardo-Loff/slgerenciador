<?php
session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'aplicacao.php';

if (
    !isset($_POST['loteId']) ||
    !isset($_POST['medicacaoLoteId']) ||
    !isset($_POST['mlMedicacaoId']) ||
    !isset($_POST['mlCausa']) ||
    !isset($_POST['mlDataInicio'])
) {
    echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
        <div class="card-header bg-danger text-white">
            <h4>Erro</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-danger">
                Dados incompletos. Certifique-se de que todos os campos obrigatórios foram preenchidos.
            </div>
            <a href="medicacaoLote.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
      </div>';
    exit;
}

$loteId = $_POST['loteId'];

$aplicacao = new Aplicacao();
$aplicacao->setLoteId($loteId);
$aplicacao->setMedicacaoId($_POST['mlMedicacaoId']);
$aplicacao->setMedicacaoCausa($_POST['mlCausa']);
$aplicacao->setMedicacaoDataInicio($_POST['mlDataInicio']);
$aplicacao->setMedicacaoLoteId($_POST['medicacaoLoteId']);

$dataFim = !empty($_POST['mlDataFim']) ? $_POST['mlDataFim'] : null;
$aplicacao->setMedicacaoDataFim($dataFim);

try {
    $sql = "UPDATE medicacaoLote SET
            medicacaoId = :medicacaoId,
            medicacaoLoteCausa = :medicacaoCausa,
            medicacaoLoteDataInicio = :medicacaoDataInicio,
            medicacaoLoteDataFim = :medicacaoDataFim
            WHERE medicacaoLoteId = :medicacaoLoteId";

    $stmt = $conn->prepare($sql);
    
    $stmt->bindValue(':medicacaoId', $aplicacao->getMedicacaoId());
    $stmt->bindValue(':medicacaoCausa', $aplicacao->getCausa());
    $stmt->bindValue(':medicacaoDataInicio', $aplicacao->getDataInicio());
    $stmt->bindValue(':medicacaoDataFim', $aplicacao->getDataFim());
    $stmt->bindValue(':medicacaoLoteId',$aplicacao->getMedicacaoLoteId());

    $stmt->execute();
    
    $_SESSION['loteIdAtual'] = $loteId;
    
    header("Location: medicacaoLote.php");
    exit();
    
} catch(PDOException $e) {
    echo "Erro ao atualizar: " . $e->getMessage();
}
?>