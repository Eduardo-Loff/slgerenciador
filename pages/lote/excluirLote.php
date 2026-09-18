<?php

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../config/conexao.php';
require 'lote.php';

if(!isset($_POST['loteId']) || empty($_POST['loteId'])){
     echo '<div class="alert alert-danger">
            ID do lote não informado
          </div>';

    echo '<a href="lotes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>';

    exit;     
}

$loteId = intval($_POST['loteId']);

if($loteId <= 0){
    echo '<div class="alert alert-danger">ID Inválido</div>';
    exit;
}

try{
    $lote = new Lote();
    $lote->setId($loteId);


    $sql = "DELETE FROM lote WHERE loteId = :loteId";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':loteId', $lote->getId(), PDO::PARAM_INT);
    $stmt->execute();

    header("Location: lotes.php");
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