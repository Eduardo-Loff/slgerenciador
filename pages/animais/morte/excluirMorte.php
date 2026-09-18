<?php

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../../config/conexao.php';
require 'morte.php';

if(!isset($_POST['morteId']) || empty($_POST['morteId'])){
     echo '<div class="alert alert-danger">
            ID do lote não informado
          </div>';

    echo '<a href="lotes.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>';

    exit;     
}

$morteId = intval($_POST['morteId']);
$morteLoteId = intval($_POST['morteLoteId']);

if($morteId <= 0){
    echo '<div class="alert alert-danger">ID Inválido</div>';
    exit;
}

try{
    $morte = new Morte();
    $morte->setId($morteId);
    $morte->setLoteId($morteLoteId);


    $sql = "DELETE FROM morte WHERE morteId = :morteId";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':morteId', $morte->getId(), PDO::PARAM_INT);
    $stmt->execute();

    $_SESSION['loteIdAtual'] = $morte->getLoteId();
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