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

require("../../../config/conexao.php");


try {

    $sql = "SELECT * FROM lote WHERE loteId = :loteId";
    $stm = $conn->prepare($sql);
    $stm->bindValue(':loteId', $loteId);
    $stm->execute();
    $rowsLote = $stm->fetch(PDO::FETCH_OBJ);

    $sql = "SELECT * FROM medicacao ORDER BY medicacaoNome ASC";
    $stm = $conn->prepare($sql);
    $stm->execute();
    $rowsMedicamentos = $stm->fetchAll(PDO::FETCH_OBJ);

    
    $sql = "SELECT ml.loteId, ml.medicacaoId, ml.medicacaoLoteCausa, ml.medicacaoLoteDataInicio, ml.medicacaoLoteDataFim, ml.medicacaoLoteId, 
            m.medicacaoNome, m.medicacaoTipo, l.loteCodigo
            FROM medicacaoLote ml
            INNER JOIN medicacao m ON ml.medicacaoId = m.medicacaoId
            INNER JOIN lote l ON ml.loteId = l.loteId
            WHERE ml.loteId = :loteId";

    $stm = $conn->prepare($sql);
    $stm->bindValue(':loteId', $loteId);
    $stm->execute();
    $rowsMedicacaoLote = $stm->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
        
        echo "Erro no banco de dados: " . $e->getMessage();
        $rowsMedicacaoLote = [];
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Medicação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../../assets/SL_icone2.png">
    <style>
        :root { --primary-green: #006432; --dark-green: #004d26; --light-bg: #f8f9fa; }
        body { background-color: var(--light-bg); font-family: system-ui, sans-serif; }
        .sidebar { background-color: var(--primary-green); color: white; z-index: 1030; }
        @media (min-width: 768px) { .sidebar { min-height: 100vh; } }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); font-size: 1.1rem; transition: 0.2s; }
        .sidebar .nav-link:hover { color: white; background-color: var(--dark-green); border-radius: 4px; }
        .table-custom-header { background-color: var(--primary-green) !important; color: white !important; }
        .btn-green { background-color: var(--primary-green); color: white; border: none; }
        .btn-green:hover { background-color: var(--dark-green); color: white; }
        .border-green { border-bottom: 3px solid var(--primary-green); }
        .secao-titulo { color: var(--primary-green); font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>

<header class="navbar navbar-dark sticky-top d-md-none p-3 shadow-sm" style="background-color: var(--primary-green);">
    <a class="navbar-brand fw-bold" href="#">SL | Gerenciador</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>

<div class="container-fluid">
    <div class="row">
        <nav class="col-12 col-md-3 col-lg-2 sidebar p-4 d-flex flex-column">
            <h3 class="title-underline fw-bold mb-4">SL | Gerenciador</h3>
            <p class="mb-1 text-white-50 small">Tela Atual:</p>
            <h5 class="fw-bold mb-4">Medicações Lote</h5>
            <ul class="nav flex-column gap-2 flex-row flex-md-column flex-wrap">
                <li class="nav-item me-3 me-md-0">
                    <form action="../../lote/loteView.php" method="post" class="m-0 p-0">
                        <input type="hidden" name="loteId" value="<?= $loteId ?>">
                        <button type="submit" class="btn btn-link nav-link p-0 text-start w-100 border-0 shadow-none bg-transparent">
                            <i class="bi bi-arrow-left-circle me-2"></i>Voltar ao Lote
                        </button>
                    </form>
                </li>
                <li class="nav-item me-3 me-md-0"><a class="nav-link p-0" href="../../dashboard.php">Início</a></li>
                <li class="nav-item"><a class="nav-link p-0" href="../medicacao.php">Medicações</a></li>
                <li class="nav-item ms-auto ms-md-0">
                    <a href="../../login/logout.php" class="nav-link p-0 text-warning">Sair</a>
                </li>
            </ul>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center border-green pb-2 mb-4 flex-wrap gap-2">
                <h2 class="fw-bold text-success m-0 fs-3 fs-md-2">
                    Lote: <span class="text-success"><?= $rowsLote->loteCodigo ?? '---' ?></span> 
                    <span class="text-muted fs-4 fw-normal mx-2">|</span> 
                    <i class="bi bi-capsule-pill text-success fs-3"></i> Medicação
                </h2>
            </div>

            <h4 class="secao-titulo">Injetáveis</h4>
            <div class="table-responsive bg-white shadow-sm rounded mb-4">
                <table class="table table-bordered align-middle m-0">
                    <thead>
                        <tr class="table-custom-header text-center">
                            <th>Nome</th>
                            <th>Causa</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $hasInjetavel = false;
                    foreach($rowsMedicacaoLote as $ma): 
                        if($ma->medicacaoTipo == 'Injetável' or $ma->medicacaoTipo == 'Injetavel'): 
                            $hasInjetavel = true;
                    ?>
                    <tr class="text-center">
                        <td><?= htmlspecialchars($ma->medicacaoNome) ?></td>
                        <td><?= htmlspecialchars($ma->medicacaoLoteCausa) ?></td> 
                        <td><?= date('d/m/Y', strtotime($ma->medicacaoLoteDataInicio)) ?></td> 
                        <td><?= $ma->medicacaoLoteDataFim ? date('d/m/Y', strtotime($ma->medicacaoLoteDataFim)) : '---' ?></td> 
                        <td>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditar"
                                data-bs-medloteid="<?= $ma->medicacaoLoteId ?>" 
                                data-bs-loteid="<?= $ma->loteId ?>"
                                data-bs-medid="<?= $ma->medicacaoId ?>" 
                                data-bs-causa="<?= $ma->medicacaoLoteCausa ?>" 
                                data-bs-inicio="<?= date('Y-m-d',strtotime($ma->medicacaoLoteDataInicio)) ?>" 
                                data-bs-fim="<?= date('Y-m-d',strtotime($ma->medicacaoLoteDataFim)) ?>"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeletar" 
                                data-bs-loteid="<?= $ma->loteId ?>"
                                data-bs-medloteid="<?= $ma->medicacaoLoteId ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <?php endif; endforeach; if(!$hasInjetavel): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Nenhum registro injetável.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h4 class="secao-titulo">Oral Via Água</h4>
            <div class="table-responsive bg-white shadow-sm rounded mb-4">
                <table class="table table-bordered align-middle m-0">
                    <thead>
                        <tr class="table-custom-header text-center">
                            <th>Nome</th>
                            <th>Causa</th>
                            <th>Data Início</th>
                            <th>Data Fim</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $hasOral = false;
                    foreach($rowsMedicacaoLote as $ma): 
                        if($ma->medicacaoTipo == 'Oral'): 
                            $hasOral = true;
                    ?>
                    <tr class="text-center">
                        <td><?= htmlspecialchars($ma->medicacaoNome) ?></td>
                        <td><?= htmlspecialchars($ma->medicacaoLoteCausa) ?></td> <td><?= date('d/m/Y', strtotime($ma->medicacaoLoteDataInicio)) ?></td> <td><?= $ma->medicacaoLoteDataFim ? date('d/m/Y', strtotime($ma->medicacaoLoteDataFim)) : '---' ?></td> <td>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalEditar" 
                                data-bs-medloteid="<?= $ma->medicacaoLoteId ?>"
                                data-bs-loteid="<?= $ma->loteId ?>"
                                data-bs-medid="<?= $ma->medicacaoId ?>" 
                                data-bs-causa="<?= $ma->medicacaoLoteCausa ?>" 
                                data-bs-inicio="<?= date('Y-m-d',strtotime($ma->medicacaoLoteDataInicio)) ?>" 
                                data-bs-fim="<?= date('Y-m-d',strtotime($ma->medicacaoLoteDataFim)) ?>"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalDeletar" 
                                data-bs-loteid="<?= $ma->loteId ?>"
                                data-bs-medloteid="<?= $ma->medicacaoLoteId ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <?php endif; endforeach; if(!$hasOral): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">Nenhum registro oral.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-green btn-lg px-5 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCriar">Nova Medicação</button>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="modalCriar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: var(--primary-green);">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Registrar Medicação</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="criarAplicacao.php" method="POST">
                <input type="hidden" name="mlLoteId" value="<?= $loteId ?>">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Medicação (Tipo)</label>
                        <select class="form-select" name="mlMedicacaoId" required>
                            <option value="" disabled selected>Selecione a medicação...</option>
                            <?php foreach($rowsMedicamentos as $lm): ?>
                                <option value="<?= $lm->medicacaoId ?>"><?= htmlspecialchars($lm->medicacaoNome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Causa / Motivo</label>
                        <input type="text" class="form-control" name="mlCausa" placeholder="Ex: Diarreia, Tosse..." required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data Início</label>
                            <input type="date" class="form-control" name="mlDataInicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data Fim (Opcional)</label>
                            <input type="date" class="form-control" name="mlDataFim">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-green">Salvar Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Editar Aplicação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="editarAplicacao.php" method="POST">
                <input type="hidden" id="editLoteId" name="loteId">
                <input type="hidden" id="editMedicacaoLoteId" name="medicacaoLoteId">

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Medicação</label>
                        <select class="form-select" id="editMedId" name="mlMedicacaoId" required>
                            <?php foreach($rowsMedicamentos as $lm): ?>
                                <option value="<?= $lm->medicacaoId ?>"><?= htmlspecialchars($lm->medicacaoNome) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Causa / Motivo</label>
                        <input type="text" class="form-control" id="editCausa" name="mlCausa" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data Início</label>
                            <input type="date" class="form-control" id="editInicio" name="mlDataInicio" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data Fim</label>
                            <input type="date" class="form-control" id="editFim" name="mlDataFim">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeletar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-trash me-2"></i>Remover Aplicação</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="excluirAplicacao.php" method="POST">
                <input type="hidden" id="medicacaoLoteId" name="medicacaoLoteId">
                <input type="hidden" id="deleteLoteId" name="loteId">
                
                <div class="modal-body text-center py-4">
                    <p class="fs-5">Deseja realmente excluir este registro de medicação do lote?</p>
                    <p class="text-danger small">Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Script para preencher modais com dados da tabela
    const modalEditar = document.getElementById('modalEditar');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', event => {
            const btn = event.relatedTarget;
            modalEditar.querySelector('#editLoteId').value = btn.getAttribute('data-bs-loteid');
            modalEditar.querySelector('#editMedicacaoLoteId').value = btn.getAttribute('data-bs-medloteid');
            modalEditar.querySelector('#editMedId').value = btn.getAttribute('data-bs-medid');
            modalEditar.querySelector('#editCausa').value = btn.getAttribute('data-bs-causa');
            modalEditar.querySelector('#editInicio').value = btn.getAttribute('data-bs-inicio');
            modalEditar.querySelector('#editFim').value = btn.getAttribute('data-bs-fim');
        });
    }

    const modalDeletar = document.getElementById('modalDeletar');
    if (modalDeletar) {
        modalDeletar.addEventListener('show.bs.modal', event => {
            const btn = event.relatedTarget;
            modalDeletar.querySelector('#medicacaoLoteId').value = btn.getAttribute('data-bs-medloteid');
            modalDeletar.querySelector('#deleteLoteId').value = btn.getAttribute('data-bs-loteid');
        });
    }
</script>
</body>
</html>