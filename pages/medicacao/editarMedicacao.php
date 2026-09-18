<?php

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}


require '../../config/conexao.php';
require 'medicamento.php';

?>

<div class="container mt-5">

<?php

if(
    !isset($_POST['id']) ||
    !isset($_POST['nome']) ||
    !isset($_POST['tipo']) 
) {

    echo '<div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4>Erro</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Dados incompletos. Certifique-se de que todos os campos foram preenchidos.
                </div>
                <a href="medicacao.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';

    exit;
}

$medicamento = new Medicacao();

$medicamento->setId($_POST['id']);
$medicamento->setNome($_POST['nome']);
$medicamento->setTipo($_POST['tipo']);


try {

    $sql = "UPDATE medicacao SET
                medicacaoNome = :nome,
                medicacaoTipo = :tipo
            WHERE medicacaoId = :id";

    $stmt = $conn->prepare($sql);

    $stmt->bindValue(':nome', $medicamento->getNome());
    $stmt->bindValue(':tipo', $medicamento->getTipo());
    $stmt->bindValue(':id', $medicamento->getId());

    $stmt->execute();

    header("Location: medicacao.php");
    exit;

} catch (PDOException $e) {

    echo '<div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h4>Erro ao Atualizar</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-danger">
                    Erro ao atualizar: ' . $e->getMessage() . '
                </div>
                <a href="medicacao.php" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </div>
          </div>';
}
?>