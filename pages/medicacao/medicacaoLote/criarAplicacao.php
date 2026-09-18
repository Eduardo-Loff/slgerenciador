<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'aplicacao.php';

if(
    !isset($_POST['mlMedicacaoId']) || 
    !isset($_POST['mlLoteId']) || 
    !isset($_POST['mlCausa']) ||
    !isset($_POST['mlDataInicio']) ||
    !isset($_POST['mlDataFim'])
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

// Cria Objeto Lote

$aplicacao = new Aplicacao();

$aplicacao->setMedicacaoId($_POST['mlMedicacaoId']);
$aplicacao->setLoteId($_POST['mlLoteId']);
$aplicacao->setMedicacaoCausa($_POST['mlCausa']);
$aplicacao->setMedicacaoDataFim($_POST['mlDataFim']);
$aplicacao->setMedicacaoDataInicio($_POST['mlDataInicio']);



// Inserção no Banco

try{

    $sql = "INSERT INTO medicacaoLote
            (loteId, medicacaoId, medicacaoLoteDataInicio, medicacaoLoteDataFim, medicacaoLoteCausa)
            VALUES
            (:loteId, :medicacaoId, :medicacaoLoteDataInicio, :medicacaoLoteDataFim, :medicacaoLoteCausa)";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':medicacaoId', $aplicacao->getMedicacaoId());
    $stmt->bindValue(':loteId', $aplicacao->getLoteId());
    $stmt->bindValue(':medicacaoLoteDataInicio', $aplicacao->getDataInicio());
    $stmt->bindValue(':medicacaoLoteDataFim', $aplicacao->getDataFim());
    $stmt->bindValue(':medicacaoLoteCausa', $aplicacao->getCausa());
    

    $stmt->execute();

    $_SESSION['loteIdAtual'] = $aplicacao->getLoteId();
    header("Location: medicacaoLote.php");        
    exit();

    exit;

} catch(PDOException $e){


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