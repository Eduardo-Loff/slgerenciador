<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

if(isset($_POST['loteId'])){

    $loteId = $_POST['loteId'];

}elseif(isset($_SESSION['loteIdAtual'])){

    $loteId = $_SESSION['loteIdAtual'];

}else{

    header('Location:lotes.php');
    exit;

}

require("../../config/conexao.php");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Lote View</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/SL_icone2.png">
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Customização da Barra Lateral (Mantendo seu padrão) */
        .sidebar {
            background-color: #006b3f;
            color: white;
        }
        @media (min-width: 768px) {
            .sidebar {
                min-height: 100vh;
            }
        }
        .sidebar .nav-link {
            color: white;
            font-size: 1.25rem;
            padding: 8px 0;
            transition: opacity 0.2s;
        }
        .sidebar .nav-link:hover {
            opacity: 0.8;
        }
        .sidebar .title-underline {
            border-bottom: 2px solid white;
            padding-bottom: 10px;
        }
        
        /* Estilização da Visualização do Lote */
        .text-verde-principal {
            color: #006b3f;
        }
        .linha-separadora {
            border-top: 3px solid #006b3f;
            opacity: 1;
        }
        
        /* Divisórias verticais customizadas para telas grandes (MD para cima) */
        @media (min-width: 768px) {
            .divisor-vertical {
                border-right: 2px solid #006b3f;
            }
        }

        /* Botões Customizados de Ações Inferiores */
        .btn-acao-lote {
            background-color: #006b3f;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 12px 20px;
            font-size: 1.1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: background-color 0.2s, transform 0.1s;
            width: 100%;
        }
        .btn-acao-lote:hover {
            background-color: #00522e;
            color: white;
            transform: translateY(-2px);
        }
        .btn-acao-lote i {
            font-size: 1.4rem;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">

            <nav class="col-12 col-md-3 col-lg-2 sidebar p-4 d-flex flex-column">
                <h3 class="title-underline fw-bold mb-3 mb-md-4">SL | Gerenciador</h3>
                
                <div class="mb-3 mb-md-5">
                    <p class="mb-1 text-white-50" style="font-size: 0.9rem;">Tela Atual:</p>
                    <h5 class="fw-bold m-0">Lote-View</h5>
                </div>

                <ul class="nav flex-row flex-md-column gap-3 gap-md-2 justify-content-between justify-content-md-start">
                    <li class="nav-item">
                        <a class="nav-link p-0" href="../dashboard.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="../medicacao/medicamento.php">Medicações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="../produtor.php">Produtor</a>
                    </li>
                    <li class="nav-item ms-auto ms-md-0">
                        <a href="../login/logout.php" class="nav-link p-0 text-warning">Sair</a>
                    </li>
                </ul>
            </nav>

            <main class="col-12 col-md-9 col-lg-10 p-3 p-md-5">
                <div class="mx-auto" style="max-width: 1000px;">
                    
                    <?php 

                        try{
                            $sql = "SELECT * FROM lote WHERE loteId = $loteId";
                            $stm = $conn->prepare($sql);
                            $stm->execute();
                            $rows = $stm->fetchAll(PDO::FETCH_OBJ);
                        
                    
                    ?>

                    <?php if(count($rows)> 0): ?>

                    <?php foreach($rows as $r): ?>

                    <h1 class="fw-bold text-verde-principal mb-2">Lote: <?= htmlspecialchars($r->loteCodigo) ?></h1>
                    <hr class="linha-separadora mt-0 mb-4">

                    <div class="row g-4 text-dark mb-5">
                        
                        <div class="col-12 col-md-4 divisor-vertical">
                            <h3 class="fw-bold text-verde-principal mb-3">Resumo Geral</h3>
                            <div class="lh-lg">
                                <p class="mb-1"><strong>Codigo:</strong> <span><?= htmlspecialchars($r->loteCodigo) ?></span></p>
                                <p class="mb-1"><strong>Data Criação:</strong> <span><?=  (new DateTime($r->loteCriacao))->format('d/m/Y')?></span></p>
                                <p class="mb-1"><strong>Estado:</strong> <span><?= htmlspecialchars($r->loteEstado) ?></span></p>
                                <p class="mb-1"><strong>Observações:</strong> <span><?= htmlspecialchars($r->loteObservacoes) ?></span></p>
                            </div>
                        </div>

                    <?php endforeach; ?>
                    <?php endif; ?>

                    <?php

                    } catch (PDOException $e){
                        echo '<div class="alert alert-danger">
                            Erro: ' . $e->getMessage() . '
                        </div>';
                    }

                    ?>

                    <?php 
                    
                        try{
                            $sql = "SELECT SUM(entradaQuantidadeMachos + entradaQuantidadeFemeas) AS totalEntrada FROM entrada WHERE entradaLoteId = :loteId";
                            $stm = $conn->prepare($sql);
                            $stm->bindValue(":loteId", $loteId, PDO::PARAM_INT);
                            $stm->execute();
                            $rowEntrada = $stm->fetch(PDO::FETCH_OBJ);

                            $totalEntradas = ($rowEntrada && $rowEntrada->totalEntrada) ? $rowEntrada->totalEntrada : 0;

                            $sql = "SELECT SUM(saidaQuantidade) AS totalSaida FROM saida WHERE saidaLoteId = :loteId";
                            $stm = $conn->prepare($sql);
                            $stm->bindValue(":loteId", $loteId, PDO::PARAM_INT);
                            $stm->execute();
                            $rowSaida = $stm->fetch(PDO::FETCH_OBJ);

                            $totalSaidas = ($rowSaida && $rowSaida->totalSaida) ? $rowSaida->totalSaida : 0;

                            
                            $sql = "SELECT SUM(morteQuantidade) AS totalMorte FROM morte WHERE morteLoteId = :loteId";
                            $stm = $conn->prepare($sql);
                            $stm->bindValue(":loteId", $loteId, PDO::PARAM_INT);
                            $stm->execute();
                            $rowMorte = $stm->fetch(PDO::FETCH_OBJ);

                            $totalMortes = ($rowMorte && $rowMorte->totalMorte) ? $rowMorte->totalMorte : 0;

                            $saldo = $totalEntradas - ($totalSaidas + $totalMortes);

                    ?>

                        <div class="col-12 col-md-4 divisor-vertical">
                            <h3 class="fw-bold text-verde-principal mb-3">Animais</h3>
                            <div class="lh-lg">
                                <p class="mb-1"><strong>Entradas:</strong> <span><?= htmlspecialchars($totalEntradas) ?></span></p>
                                <p class="mb-1"><strong>Saidas:</strong> <span><?= htmlspecialchars($totalSaidas) ?></span></p>
                                <p class="mb-1"><strong>Mortes:</strong> <span><?= htmlspecialchars($totalMortes) ?></span></p>
                                <p class="mb-1"><strong>Saldo Atual:</strong> <span><?= htmlspecialchars($saldo) ?></span></p>
                            </div>
                        </div>


                    <?php

                    } catch (PDOException $e){
                        echo '<div class="alert alert-danger">
                            Erro: ' . $e->getMessage() . '
                        </div>';
                    }

                    ?>

                    <?php 
                    
                        try{
                            $sql = "SELECT SUM(racaoQuantidade) AS totalPre
                                    FROM racao
                                    WHERE racaoLoteId = :loteId
                                    AND racaoTipo = :tipo";

                            $stm = $conn->prepare($sql);
                            $stm->bindValue(":loteId", $loteId, PDO::PARAM_INT);
                            $stm->bindValue(":tipo", "Pre-Inicial", PDO::PARAM_STR);
                            $stm->execute();

                            $resultado = $stm->fetch(PDO::FETCH_ASSOC);
                            $totalPre = $resultado['totalPre'];

                            $sql = "SELECT SUM(racaoQuantidade) AS totalIn1
                                    FROM racao
                                    WHERE racaoLoteId = :loteId
                                    AND racaoTipo = :tipo";

                            $stm = $conn->prepare($sql);
                            $stm->bindValue(":loteId",$loteId,PDO::PARAM_INT);
                            $stm->bindValue(":tipo","Inicial 1",PDO::PARAM_STR);
                            $stm->execute();

                            $resultado = $stm->fetch(PDO::FETCH_ASSOC);
                            $totalIn1 = $resultado['totalIn1'];

                            $sql = "SELECT SUM(racaoQuantidade) AS totalIn2
                                    FROM racao
                                    WHERE racaoLoteId = :loteId
                                    AND racaoTipo = :tipo";

                            $stm = $conn->prepare($sql);
                            $stm->bindValue(":loteId",$loteId,PDO::PARAM_INT);
                            $stm->bindValue(":tipo","Inicial 2",PDO::PARAM_STR);
                            $stm->execute();

                            $resultado = $stm->fetch(PDO::FETCH_ASSOC);
                            $totalIn2 = $resultado['totalIn2'];
                    ?>


                        <div class="col-12 col-md-4">
                            <h3 class="fw-bold text-verde-principal mb-3">Rações</h3>
                            <div class="lh-lg">
                                <p class="mb-1"><strong>Pré-Inicial (kg):</strong> <span><?= htmlspecialchars($totalPre) ?></span></p>
                                <p class="mb-1"><strong>Inicial 1 (kg):</strong> <span><?= htmlspecialchars($totalIn1)?></span></p>
                                <p class="mb-1"><strong>Inicial 2 (kg):</strong> <span><?= htmlspecialchars($totalIn2) ?></span></p>
                            </div>
                        </div>

                  <?php

                    } catch (PDOException $e){
                        echo '<div class="alert alert-danger">
                            Erro: ' . $e->getMessage() . '
                        </div>';
                    }

                    ?>

                    </div>

                    <hr class="linha-separadora my-4">

                    <h3 class="fw-bold text-verde-principal mb-4">Ações</h3>
                    
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 col-lg-3">
                            <form action="../animais/animais.php" method="post">
                                <input type="hidden" name="loteId" id="loteId" value="<?= $loteId ?>">
                                <button type="submit" class="btn btn-acao-lote shadow-sm">
                                    <i class="bi bi-piggy-bank"></i> Animais
                                </button>
                            </form>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-3">
                            <form action="../racao/racoes.php" method="post">
                                <input type="hidden" name="loteId" value="<?=  $loteId ?>">
                                <button type="submit" class="btn btn-acao-lote shadow-sm">
                                    <i class="bi bi-moisture"></i> Rações
                                </button>
                            </form>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-3">
                            <form action="../medicacao/medicacaoLote/medicacaoLote.php" method="post">
                                <input type="hidden" name="loteId" value="<?=  $loteId ?>">
                                <button type="submit" class="btn btn-acao-lote shadow-sm">
                                    <i class="bi bi-prescription2"></i></i> Medicações
                                </button>
                        </div>
                        
                        <div class="col-12 col-sm-6 col-lg-3" style="display: none;">
                            <a href="lotes.php" class="btn btn-acao-lote shadow-sm bg-secondary">
                                <i class="bi bi-x-circle"></i> Encerrar
                            </a>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>