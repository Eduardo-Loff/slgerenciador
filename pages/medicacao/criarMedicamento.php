<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require '../../config/conexao.php';
require 'medicamento.php';

if(
    !isset($_POST['medicacaoNome']) || !isset($_POST['medicacaoTipo'])
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

$medicacao = new Medicacao();

$medicacao->setNome($_POST['medicacaoNome']);
$medicacao->setTipo($_POST['medicacaoTipo']);

// Inserção no Banco

try{

    $sql = "INSERT INTO medicacao
            (medicacaoNome, medicacaoTipo)
            VALUES
            (:medicacaoNome, :medicacaoTipo)";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':medicacaoNome', $medicacao->getNome());
    $stmt->bindValue(':medicacaoTipo', $medicacao->getTipo());

    $stmt->execute();

    header("Location: medicacao.php");

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