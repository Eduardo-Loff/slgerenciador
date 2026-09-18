<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../config/conexao.php';
require 'lote.php';

if(
    !isset($_POST['lotePropriedadeId']) ||
    !isset($_POST['loteCodigo']) ||
    !isset($_POST['loteObs']) ||
    !isset($_POST['loteCriacao'])
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

$lote = new Lote();

$lote->setPropriedadeId($_POST['lotePropriedadeId']);
$lote->setCodigo($_POST['loteCodigo']);
$lote->setObservacoes($_POST['loteObs']);
$lote->setCriacao($_POST['loteCriacao']);

try{

    $sql = "INSERT INTO lote
    (lotePropriedadeId, loteCodigo, loteObservacoes, loteCriacao, loteEstado)
    VALUES
    (:lotePropriedadeId, :loteCodigo, :loteObs, :loteCriacao, 'Ativo')";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':lotePropriedadeId',$lote->getPropriedadeId());
    $stmt->bindValue(':loteCodigo',$lote->getCodigo());
    $stmt->bindValue(':loteObs',$lote->getObservacoes());
    $stmt->bindValue(':loteCriacao',$lote->getCriacao());

    $stmt->execute();

    $_SESSION['propriedadeIdAtual'] = $lote->getPropriedadeId();

    header("Location: lotes.php");

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