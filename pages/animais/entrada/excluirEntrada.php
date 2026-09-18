<?php

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'entrada.php';

if(!isset($_POST['entradaId']) || empty($_POST['entradaId'])){
     echo '<div class="alert alert-danger">
            ID do lote não informado
          </div>';

    echo '<a href="lotes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>';

    exit;     
}

$entradaId = intval($_POST['entradaId']);
$entradaLoteId = intval($_POST['entradaLoteId']);

if($entradaId <= 0){
    echo '<div class="alert alert-danger">ID Inválido</div>';
    exit;
}

try{
    $entrada = new Entrada();
    $entrada->setId($entradaId);
    $entrada->setLoteId($entradaLoteId);


    $sql = "DELETE FROM entrada WHERE entradaId = :entradaId";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':entradaId', $entrada->getId(), PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['loteIdAtual'] = $entrada->getLoteId();
    header("Location: ../animais.php");
    exit;

} catch (PDOException $e) {

    echo '<div class="card shadow mt-5 mx-auto" style="max-width: 500px;">
            <div class="card-header bg-danger text-white">
                <h4>Erro</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Erro ao excluir: ' . $e->getMessage() . '
                </div>
                <a href="lotes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';
}
?>