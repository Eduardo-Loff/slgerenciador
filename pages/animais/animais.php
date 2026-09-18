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
    <title>SL | Gerenciador - Gerenciar Animais</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/SL_icone2.png">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { background-color: #006b3f; color: white; min-height: 100vh; }
        .sidebar .nav-link { color: white; font-size: 1.1rem; transition: 0.2s; }
        .sidebar .nav-link:hover { opacity: 0.8; }
        .sidebar .title-underline { border-bottom: 2px solid white; padding-bottom: 10px; }

        .text-verde-principal { color: #006b3f; }
        .bg-verde-principal { background-color: #006b3f; color: white; }
        
        /* Estilo das Tabelas */
        .table-custom thead { background-color: #006b3f; color: white; }
        .table-custom th { font-weight: 500; text-align: center; vertical-align: middle; }
        .table-custom td { text-align: center; vertical-align: middle; }

        .btn-verde { background-color: #006b3f; color: white; border-radius: 10px; border: none; }
        .btn-verde:hover { background-color: #00522e; color: white; }
        
        .btn-acao { padding: 4px 8px; font-size: 0.9rem; border-radius: 8px; }
        
        .secao-titulo { border-left: 5px solid #006b3f; padding-left: 15px; margin-bottom: 20px; color: #006b3f; font-weight: bold; }

        /* Estilo para a Linha Divisória de Indicadores */
        .divisor-indicador { border-right: 2px solid #006b3f; }
        @media (max-width: 767.98px) {
            .divisor-indicador { border-right: none; border-bottom: 2px solid #006b3f; padding-bottom: 15px; margin-bottom: 15px; }
            .sidebar { min-height: auto; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-12 col-md-3 col-lg-2 sidebar p-4 d-flex flex-column">
            <h3 class="title-underline fw-bold mb-4">SL | Gerenciador</h3>
            <p class="mb-1 text-white-50 small">Tela Atual:</p>
            <h5 class="fw-bold mb-4">Animais</h5>
            <ul class="nav flex-column gap-2 flex-row flex-md-column flex-wrap">
                <li class="nav-item me-3 me-md-0">
                    <form action="../lote/loteView.php" method="post" class="m-0 p-0">
                        <input type="hidden" name="loteId" value="<?= $loteId ?>">
                        <button type="submit" class="btn btn-link nav-link p-0 text-start w-100 border-0 shadow-none bg-transparent">
                            <i class="bi bi-arrow-left-circle me-2"></i>Voltar ao Lote
                        </button>
                    </form>
                </li>
                <li class="nav-item me-3 me-md-0"><a class="nav-link p-0" href="../dashboard.php">Início</a></li>
                <li class="nav-item"><a class="nav-link p-0" href="../medicacao/medicamento.php">Medicações</a></li>
                <li class="nav-item ms-auto ms-md-0">
                    <a href="../login/logout.php" class="nav-link p-0 text-warning">Sair</a>
                </li>
            </ul>
        </nav>

        <main class="col-12 col-md-9 col-lg-10 p-3 p-md-5">

            <?php 
                try{
                    $sql = "SELECT * FROM lote WHERE loteId = :loteId";
                    $stm = $conn->prepare($sql);
                    $stm->bindValue(":loteId",$loteId);
                    $stm->execute();
                    $rowsLote = $stm->fetchAll(PDO::FETCH_OBJ);

                    $sql = "SELECT * FROM entrada WHERE entradaLoteId = :loteId ";
                    $stm = $conn->prepare($sql);
                    $stm->bindValue(":loteId",$loteId);
                    $stm->execute();
                    $rowsEntrada = $stm->fetchAll(PDO::FETCH_OBJ);

                    $sql = "SELECT * FROM saida WHERE saidaLoteId = :loteId";
                    $stm = $conn->prepare($sql);
                    $stm->bindValue(":loteId",$loteId);
                    $stm->execute();
                    $rowsSaida = $stm->fetchAll(PDO::FETCH_OBJ);

                    $sql = "SELECT * FROM morte WHERE morteLoteId = :loteId";
                    $stm = $conn->prepare($sql);
                    $stm->bindValue(":loteId",$loteId);
                    $stm->execute();
                    $rowsMorte = $stm->fetchAll(PDO::FETCH_OBJ);

                    $totalMachos = 0; $totalFemeas = 0; $somaPmEntrada = 0; $qtdEntradas = count($rowsEntrada);
                    foreach($rowsEntrada as $ent) {
                        $totalMachos += intval($ent->entradaQuantidadeMachos);
                        $totalFemeas += intval($ent->entradaQuantidadeFemeas);
                        $somaPmEntrada += floatval($ent->entradaPm);
                    }
                    $totalAnimaisEntrados = $totalMachos + $totalFemeas;
                    $pmEntradasFinal = $qtdEntradas > 0 ? ($somaPmEntrada / $qtdEntradas) : 0;

                    $somaPmSaida = 0; $qtdSaidas = count($rowsSaida);
                    foreach($rowsSaida as $sai) { $somaPmSaida += floatval($sai->saidaPm); }
                    $pmSaidasFinal = $qtdSaidas > 0 ? ($somaPmSaida / $qtdSaidas) : 0;

                    $totalMortos = 0;
                    foreach($rowsMorte as $mor) { $totalMortos += intval($mor->morteQuantidade); }
                    $porcentagemMortalidade = $totalAnimaisEntrados > 0 ? (($totalMortos / $totalAnimaisEntrados) * 100) : 0;
            ?>
            
            <?php foreach($rowsLote as $r): ?>
                <div class="d-flex align-items-center mb-4 flex-wrap">
                    <h1 class="fw-bold text-verde-principal m-0 fs-2 fs-md-1">Lote: <?= htmlspecialchars($r->loteCodigo) ?> | <i class="bi bi-piggy-bank"></i> Animais</h1>
                </div>
            <?php endforeach; ?>

            <div class="card card-body shadow-sm border-0 mb-5 py-4 px-4">
                <div class="row text-verde-principal">
                    <div class="col-12 col-md-4 divisor-indicador">
                        <h4 class="fw-bold mb-3">Resumo Técnico</h4>
                        <p class="mb-2"><strong>Lote Ref:</strong> <?= isset($rowsLote[0]) ? htmlspecialchars($rowsLote[0]->loteCodigo) : '---' ?></p>
                        <p class="mb-2"><strong>Status Lote:</strong> <span class="badge bg-success">Ativo</span></p>
                    </div>
                    <div class="col-12 col-md-4 divisor-indicador ps-md-4">
                        <h4 class="fw-bold mb-3">Pesos Médios</h4>
                        <p class="mb-2"><strong>P.M Entradas:</strong> <?= number_format($pmEntradasFinal, 2, ',', '.') ?> kg</p>
                        <p class="mb-2"><strong>P.M Saídas:</strong> <?= number_format($pmSaidasFinal, 2, ',', '.') ?> kg</p>
                    </div>
                    <div class="col-12 col-md-4 ps-md-4">
                        <h4 class="fw-bold mb-3">Desempenho</h4>
                        <p class="mb-2"><strong>% Mortalidade:</strong> <?= number_format($porcentagemMortalidade, 2, ',', '.') ?>%</p>
                    </div>
                </div>
            </div>

            <section class="mb-5">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-end gap-2 mb-3">
                    <h3 class="secao-titulo m-0">Entradas</h3>
                    <button class="btn btn-verde shadow-sm w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalNovaEntrada"><i class="bi bi-plus-lg"></i> Nova Entrada</button>
                </div>
                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-hover table-custom m-0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>NF</th>
                                <th>Machos</th>
                                <th>Fêmeas</th>
                                <th>P.M (kg)</th>
                                <th>P.T (kg)</th>
                                <th>Origem</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($rowsEntrada) > 0): ?>
                            <?php foreach($rowsEntrada as $r): ?>
                            <tr>
                                <td><?= (new DateTime($r->entradaData))->format('d/m/Y')?></td>
                                <td><?= htmlspecialchars($r->entradaNf) ?></td>
                                <td><?= htmlspecialchars($r->entradaQuantidadeMachos) ?></td>
                                <td><?= htmlspecialchars($r->entradaQuantidadeFemeas) ?></td>
                                <td><?= htmlspecialchars(($r->entradaPm)) ?></td>
                                <td><?= htmlspecialchars($r->entradaPt) ?></td>
                                <td><?= htmlspecialchars($r->entradaOrigem) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-primary btn-acao" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarEntrada"
                                                data-id="<?= htmlspecialchars($r->entradaId) ?>"
                                                data-data="<?= htmlspecialchars($r->entradaData) ?>"
                                                data-nf="<?= htmlspecialchars($r->entradaNf) ?>"
                                                data-origem="<?= htmlspecialchars($r->entradaOrigem) ?>"
                                                data-machos="<?= htmlspecialchars($r->entradaQuantidadeMachos) ?>"
                                                data-femeas="<?= htmlspecialchars($r->entradaQuantidadeFemeas) ?>"
                                                data-pm="<?= htmlspecialchars($r->entradaPm) ?>"
                                                data-pt="<?= htmlspecialchars($r->entradaPt) ?>"
                                                onclick="preencherEdicaoEntrada(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-acao" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalExcluirEntrada"
                                                data-id="<?= htmlspecialchars($r->entradaId) ?>"
                                                onclick="configurarExclusaoEntrada(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="8">Não Existem Entradas Nesse Lote</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-5">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-end gap-2 mb-3">
                    <h3 class="secao-titulo m-0">Saídas</h3>
                    <button class="btn btn-verde shadow-sm w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalNovaSaida"><i class="bi bi-plus-lg"></i> Nova Saída</button>
                </div>
                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-hover table-custom m-0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>NF</th>
                                <th>GTA</th>
                                <th>Quant.</th>
                                <th>P.M (kg)</th>
                                <th>Destino</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($rowsSaida) > 0): ?>
                            <?php foreach($rowsSaida as $r): ?>
                            <tr>
                                <td><?= (new DateTime($r->saidaData))->format('d/m/Y') ?></td>
                                <td><?= htmlspecialchars($r->saidaNf) ?></td>
                                <td><?= htmlspecialchars($r->saidaGta) ?></td>
                                <td><?= htmlspecialchars($r->saidaQuantidade) ?></td>
                                <td><?= htmlspecialchars($r->saidaPm) ?></td>
                                <td><?= htmlspecialchars($r->saidaDestino) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-primary btn-acao" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarSaida"
                                                data-id="<?= htmlspecialchars($r->saidaId) ?>"
                                                data-data="<?= htmlspecialchars($r->saidaData) ?>"
                                                data-nf="<?= htmlspecialchars($r->saidaNf) ?>"
                                                data-gta="<?= htmlspecialchars($r->saidaGta) ?>"
                                                data-quantidade="<?= htmlspecialchars($r->saidaQuantidade) ?>"
                                                data-pm="<?= htmlspecialchars($r->saidaPm) ?>"
                                                data-destino="<?= htmlspecialchars($r->saidaDestino) ?>"
                                                onclick="preencherEdicaoSaida(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-acao" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalExcluirSaida"
                                                data-id="<?= htmlspecialchars($r->saidaId) ?>"
                                                onclick="configurarExclusaoSaida(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7">Não Existem Saídas Nesse Lote</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="mb-5">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-end gap-2 mb-3">
                    <h3 class="secao-titulo m-0">Mortes</h3>
                    <button class="btn btn-verde shadow-sm w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalNovaMorte"><i class="bi bi-plus-lg"></i> Nova Morte</button>
                </div>
                <div class="table-responsive shadow-sm rounded">
                    <table class="table table-hover table-custom m-0">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Quantidade</th>
                                <th>P.M (kg)</th>
                                <th>Causa</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($rowsMorte) > 0): ?>
                            <?php foreach($rowsMorte as $r): ?>
                            <tr>
                                <td><?= (new DateTime($r->morteData))->format('d/m/Y') ?></td>
                                <td><?= htmlspecialchars($r->morteQuantidade) ?></td>
                                <td><?= htmlspecialchars($r->mortePm) ?></td>
                                <td><?= htmlspecialchars($r->morteCausa) ?></td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button class="btn btn-sm btn-outline-primary btn-acao" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarMorte"
                                                data-id="<?= htmlspecialchars($r->morteId) ?>"
                                                data-data="<?= htmlspecialchars($r->morteData) ?>"
                                                data-quantidade="<?= htmlspecialchars($r->morteQuantidade) ?>"
                                                data-pm="<?= htmlspecialchars($r->mortePm) ?>"
                                                data-causa="<?= htmlspecialchars($r->morteCausa) ?>"
                                                onclick="preencherEdicaoMorte(this)">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-acao" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalExcluirMorte"
                                                data-id="<?= htmlspecialchars($r->morteId) ?>"
                                                onclick="configurarExclusaoMorte(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="5">Não Existem Registros de Mortes Nesse Lote</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>

<?php 
    } catch (PDOException $e){
         echo '<div class="alert alert-danger m-3">Erro: ' . $e->getMessage() . '</div>';
    } 
?>

<div class="modal fade" id="modalNovaEntrada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-verde-principal text-white">
                <h5 class="modal-title fw-bold">Registrar Nova Entrada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./entrada/criarEntrada.php" method="POST">
                <div class="modal-body row g-3">
                    <input type="number" name="entradaLoteId" style="display: none;" value="<?= $loteId ?>">
                    <div class="col-md-4"><label class="form-label fw-bold">Data:</label><input type="date" class="form-control" name="entradaData" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">NF:</label><input type="text" class="form-control" name="entradaNf" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Origem:</label><input type="text" class="form-control" name="entradaOrigem" required></div>
                    <div class="col-md-3"><label class="form-label fw-bold">Machos:</label><input type="number" class="form-control" name="entradaMachos" value="0"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">Fêmeas:</label><input type="number" class="form-control" name="entradaFemeas" value="0"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">P. Médio (kg):</label><input type="number" step="0.01" class="form-control" name="entradaPm"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">P. Total (kg):</label><input type="number" step="0.01" class="form-control" name="entradaPt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-verde">Salvar Entrada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovaSaida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-verde-principal text-white">
                <h5 class="modal-title fw-bold">Registrar Nova Saída</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./saida/criarSaida.php" method="POST">
                <div class="modal-body row g-3">
                    <input type="hidden" name="saidaLoteId" value="<?=  $loteId ?>">
                    <div class="col-md-4"><label class="form-label fw-bold">Data:</label><input type="date" class="form-control" name="saidaData" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">NF:</label><input type="text" class="form-control" name="saidaNf"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">GTA:</label><input type="text" class="form-control" name="saidaGta"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Quantidade:</label><input type="number" class="form-control" name="saidaQuantidade" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">P. Médio (kg):</label><input type="number" step="0.01" class="form-control" name="saidaPm"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Destino:</label><input type="text" class="form-control" name="saidaDestino"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-verde">Salvar Saída</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNovaMorte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">Registrar Óbito</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./morte/criarMorte.php" method="POST">
                <div class="modal-body row g-3">
                    <input type="hidden" name="morteLoteId" value="<?= $loteId ?>">
                    <div class="col-md-6"><label class="form-label fw-bold">Data:</label><input type="date" class="form-control" name="morteData" required></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Quantidade:</label><input type="number" class="form-control" name="morteQuantidade" required></div>
                    <div class="col-md-6"><label class="form-label fw-bold">P. Médio (Estimado):</label><input type="number" step="0.01" class="form-control" name="mortePm"></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Causa:</label><input type="text" class="form-control" name="morteCausa" placeholder="Ex: Doença..."></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarEntrada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Editar Dados da Entrada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./entrada/editarEntrada.php" method="POST">
                <div class="modal-body row g-3">
                    <input type="hidden" name="entradaLoteId" value="<?=  $loteId ?>">
                    <input type="hidden" id="editarEntradaId" name="entradaId"> 
                    
                    <div class="col-md-4"><label class="form-label fw-bold">Data:</label><input type="date" id="editData" class="form-control" name="entradaData" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">NF:</label><input type="text" id="editNf" class="form-control" name="entradaNf"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Origem:</label><input type="text" id="editOrigem" class="form-control" name="entradaOrigem"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">Machos:</label><input type="number" id="editMachos" class="form-control" name="entradaMachos"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">Fêmeas:</label><input type="number" id="editFemeas" class="form-control" name="entradaFemeas"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">P. Médio (kg):</label><input type="number" step="0.01" id="editPm" class="form-control" name="entradaPm"></div>
                    <div class="col-md-3"><label class="form-label fw-bold">P. Total (kg):</label><input type="number" step="0.01" id="editPt" class="form-control" name="entradaPt"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarSaida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Editar Dados da Saída</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./saida/editarSaida.php" method="POST">
                <div class="modal-body row g-3">
                    <input type="hidden" name="saidaLoteId" value="<?=  $loteId ?>">
                    <input type="hidden" id="editarSaidaId" name="saidaId"> 
                    
                    <div class="col-md-4"><label class="form-label fw-bold">Data:</label><input type="date" id="editSaidaData" class="form-control" name="saidaData" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">NF:</label><input type="text" id="editSaidaNf" class="form-control" name="saidaNf"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">GTA:</label><input type="text" id="editSaidaGta" class="form-control" name="saidaGta"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Quantidade:</label><input type="number" id="editSaidaQuantidade" class="form-control" name="saidaQuantidade" required></div>
                    <div class="col-md-4"><label class="form-label fw-bold">P. Médio (kg):</label><input type="number" step="0.01" id="editSaidaPm" class="form-control" name="saidaPm"></div>
                    <div class="col-md-4"><label class="form-label fw-bold">Destino:</label><input type="text" id="editSaidaDestino" class="form-control" name="saidaDestino"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarMorte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Editar Dados do Óbito</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./morte/editarMorte.php" method="POST">
                <div class="modal-body row g-3">
                    <input type="hidden" name="morteLoteId" value="<?=  $loteId ?>">
                    <input type="hidden" id="editarMorteId" name="morteId"> 
                    
                    <div class="col-md-6"><label class="form-label fw-bold">Data:</label><input type="date" id="editMorteData" class="form-control" name="morteData" required></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Quantidade:</label><input type="number" id="editMorteQuantidade" class="form-control" name="morteQuantidade" required></div>
                    <div class="col-md-6"><label class="form-label fw-bold">P. Médio (Estimado):</label><input type="number" step="0.01" id="editMortePm" class="form-control" name="mortePm"></div>
                    <div class="col-md-6"><label class="form-label fw-bold">Causa:</label><input type="text" id="editMorteCausa" class="form-control" name="morteCausa"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcluirEntrada" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Confirmar Exclusão de Entrada</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./entrada/excluirEntrada.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="entradaLoteId" value="<?= $loteId ?>">
                    <input type="hidden" id="excluirEntradaId" name="entradaId">
                    <p>Tem certeza que deseja excluir este registro de <strong>Entrada</strong>? Esta ação é permanente e reajustará o saldo total de animais.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir Entrada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcluirSaida" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Confirmar Exclusão de Saída</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./saida/excluirSaida.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="saidaLoteId" value="<?=  $loteId ?>">
                    <input type="hidden" id="excluirSaidaId" name="saidaId">
                    <p>Tem certeza que deseja excluir este registro de <strong>Saída</strong>? Esta ação é permanente e reajustará o saldo total de animais.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir Saída</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcluirMorte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="./morte/excluirMorte.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="morteLoteId" value="<?=  $loteId ?>">
                    <input type="hidden" id="excluirMorteId" name="morteId">
                    <p>Tem certeza que deseja excluir este registro de <strong>Morte</strong>? Esta ação é permanente e reajustará o saldo total de animais.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger">Sim, Excluir Morte</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function preencherEdicaoEntrada(btn) {
    document.getElementById('editarEntradaId').value = btn.getAttribute('data-id');
    document.getElementById('editData').value = btn.getAttribute('data-data');
    document.getElementById('editNf').value = btn.getAttribute('data-nf');
    document.getElementById('editOrigem').value = btn.getAttribute('data-origem');
    document.getElementById('editMachos').value = btn.getAttribute('data-machos');
    document.getElementById('editFemeas').value = btn.getAttribute('data-femeas');
    document.getElementById('editPm').value = btn.getAttribute('data-pm');
    document.getElementById('editPt').value = btn.getAttribute('data-pt');
}

function configurarExclusaoEntrada(btn) {
    document.getElementById('excluirEntradaId').value = btn.getAttribute('data-id');
}

function preencherEdicaoSaida(btn) {
    document.getElementById('editarSaidaId').value = btn.getAttribute('data-id');
    document.getElementById('editSaidaData').value = btn.getAttribute('data-data');
    document.getElementById('editSaidaNf').value = btn.getAttribute('data-nf');
    document.getElementById('editSaidaGta').value = btn.getAttribute('data-gta');
    document.getElementById('editSaidaQuantidade').value = btn.getAttribute('data-quantidade');
    document.getElementById('editSaidaPm').value = btn.getAttribute('data-pm');
    document.getElementById('editSaidaDestino').value = btn.getAttribute('data-destino');
}

function configurarExclusaoSaida(btn) {
    document.getElementById('excluirSaidaId').value = btn.getAttribute('data-id');
}

function preencherEdicaoMorte(btn) {
    document.getElementById('editarMorteId').value = btn.getAttribute('data-id');
    document.getElementById('editMorteData').value = btn.getAttribute('data-data');
    document.getElementById('editMorteQuantidade').value = btn.getAttribute('data-quantidade');
    document.getElementById('editMortePm').value = btn.getAttribute('data-pm');
    document.getElementById('editMorteCausa').value = btn.getAttribute('data-causa');
}

function configurarExclusaoMorte(btn) {
    document.getElementById('excluirMorteId').value = btn.getAttribute('data-id');
}

document.addEventListener("DOMContentLoaded", function () {

function calcularPesoTotal(modal) {
        const inputMachos = modal.querySelector('input[name="entradaMachos"]');
        const inputFemeas = modal.querySelector('input[name="entradaFemeas"]');
        const inputPm = modal.querySelector('input[name="entradaPm"]');
        const inputPt = modal.querySelector('input[name="entradaPt"]');
        if (!inputMachos || !inputFemeas || !inputPm || !inputPt) return;

        const machos = parseInt(inputMachos.value) || 0;
        const femeas = parseInt(inputFemeas.value) || 0;
        const pesoMedio = parseFloat(inputPm.value) || 0;
        const pesoTotal = (machos + femeas) * pesoMedio;
        inputPt.value = pesoTotal > 0 ? pesoTotal.toFixed(2) : "";
    }

    const modaisEntrada = ['#modalNovaEntrada', '#modalEditarEntrada'];

    modaisEntrada.forEach(seletor => {
        const modal = document.querySelector(seletor);
        if (modal) {
            const camposGatilho = [
                modal.querySelector('input[name="entradaMachos"]'),
                modal.querySelector('input[name="entradaFemeas"]'),
                modal.querySelector('input[name="entradaPm"]')
            ];
            camposGatilho.forEach(input => {
                if (input) {
                    input.addEventListener('input', () => calcularPesoTotal(modal));
                }
            });
        }
    });
});
</script>
</body>
</html>