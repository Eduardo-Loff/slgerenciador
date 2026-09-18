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
    <title>SL | Gerenciador - Rações</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/SL_icone2.png">
    <style>
        :root {
            --primary-green: #006432;
            --dark-green: #004d26;
            --light-bg: #f8f9fa;
        }
        body {
            background-color: var(--light-bg);
            font-family: system-ui, -apple-system, sans-serif;
        }
        /* Sidebar customizada */
        .sidebar {
            background-color: var(--primary-green);
            color: white;
            z-index: 1030;
        }
        @media (min-width: 768px) {
            .sidebar {
                min-height: 100vh;
            }
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover {
            color: white;
            background-color: var(--dark-green);
            border-radius: 4px;
        }
        /* Estilização dos componentes da tabela */
        .table-custom-header {
            background-color: var(--primary-green) !important;
            color: white !important;
        }
        .btn-green {
            background-color: var(--primary-green);
            color: white;
            border: none;
        }
        .btn-green:hover {
            background-color: var(--dark-green);
            color: white;
        }
        .border-green {
            border-bottom: 3px solid var(--primary-green);
        }
        /* Ajuste do topo no mobile para não cobrir o conteúdo */
        @media (max-width: 767.98px) {
            main {
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>

<?php 

    try{
        $sql = "SELECT * FROM lote WHERE loteId = :loteId";
        $stm = $conn->prepare($sql);
        $stm->bindValue(':loteId', $loteId);
        $stm->execute();
        $rowsLote = $stm->fetchAll(PDO::FETCH_OBJ); 

        // Certifique-se de que sua tabela racao possua uma chave primária racaoId
        $sql = "SELECT * FROM racao WHERE racaoLoteId = :loteId";
        $stm = $conn->prepare($sql);
        $stm->bindValue(':loteId',$loteId);
        $stm->execute();
        $rowsRacoes = $stm->fetchAll(PDO::FETCH_OBJ);

?>

<header class="navbar navbar-dark sticky-top d-md-none p-3 shadow-sm" style="background-color: var(--primary-green);">
    <a class="navbar-brand fw-bold" href="#">SL | Gerenciador</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
</header>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse p-3">
            <div class="position-sticky">
                <h4 class="fw-bold mb-4 d-none d-md-block">SL | Gerenciador</h4>
                <div class="mb-4 mt-2 mt-md-0">
                    <small class="text-white-50 d-block">Tela Atual:</small>
                    <span class="fw-semibold">Rações</span>
                </div>
                <ul class="nav flex-column gap-2">
                    <li class="nav-item">
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
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex justify-content-between align-items-center border-green pb-2 mb-4 flex-wrap gap-2">
                <?php foreach($rowsLote as $rL): ?>
                    <h2 class="fw-bold text-success m-0 fs-3 fs-md-2">
                        Lote: <span class="text-success"><?= htmlspecialchars($rL->loteCodigo) ?></span> 
                        <span class="text-muted fs-4 fw-normal mx-1 mx-md-2">|</span> 
                        <i class="bi bi-egg-fried text-success fs-3"></i> Rações
                    </h2>
                <?php endforeach; ?>
            </div>

            <h3 class="text-success fw-bold mb-3 fs-4 fs-md-3">Rações</h3>
            
            <div class="table-responsive bg-white shadow-sm rounded mb-4">
                <table class="table table-bordered align-middle m-0">
                    <thead>
                        <tr class="table-custom-header text-center">
                            <th>Data</th>
                            <th>NF</th>
                            <th>Tipo</th>
                            <th>Quantidade (kg)</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($rowsRacoes) > 0): ?>
                        <?php foreach($rowsRacoes as $rR): ?>
                        <tr class="text-center">
                            <td><?= (new DateTime($rR->racaoData))->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($rR->racaoNf) ?></td>
                            <td><?= htmlspecialchars($rR->racaoTipo) ?></td>
                            <td><?= htmlspecialchars($rR->racaoQuantidade) ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-outline-warning" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarRacao"
                                            data-bs-id="<?= $rR->racaoId ?>"
                                            data-bs-data="<?= $rR->racaoData ?>"
                                            data-bs-nf="<?= htmlspecialchars($rR->racaoNf) ?>"
                                            data-bs-tipo="<?= htmlspecialchars($rR->racaoTipo) ?>"
                                            data-bs-quantidade="<?= $rR->racaoQuantidade ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    
                                    <button class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDeletarRacao"
                                            data-bs-id="<?= $rR->racaoId ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">Não Existem Rações Nesse Lote</td>
                        </tr>
                        <?php endif; ?>    
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-green btn-lg px-4 rounded-3 shadow-sm w-100 w-sm-auto" data-bs-toggle="modal" data-bs-target="#modalCriarRacao">
                    Nova Ração
                </button>
            </div>
        </main>
    </div>
</div>

<?php 
    } catch (PDOException $e){
         echo '<div class="alert alert-danger m-3">Erro: ' . $e->getMessage() . '</div>';
    } 
?>

<div class="modal fade" id="modalCriarRacao" tabindex="-1" aria-labelledby="modalCriarRacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: var(--primary-green);">
                <h5 class="modal-title" id="modalCriarRacaoLabel"><i class="bi bi-plus-circle me-2"></i>Adicionar Nova Ração</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="criarRacao.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="racaoLoteId" value="<?= $loteId ?>">
                    <div class="mb-3">
                        <label for="racaoData" class="form-label fw-bold">Data</label>
                        <input type="date" class="form-control" id="racaoData" name="racaoData" required>
                    </div>
                    <div class="mb-3">
                        <label for="racaoNf" class="form-label fw-bold">Nota Fiscal (NF)</label>
                        <input type="text" class="form-control" id="racaoNf" name="racaoNf" placeholder="Digite o número da NF" required>
                    </div>
                    <div class="mb-3">
                        <label for="racaoTipo" class="form-label fw-bold">Tipo</label>
                        <select class="form-select" id="racaoTipo" name="racaoTipo" required>
                            <option value="" selected disabled>Selecione o tipo...</option>
                            <option value="Pré-Inicial">Pré-Inicial</option>
                            <option value="Inicial 1">Inicial 1</option>
                            <option value="Inicial 2">Inicial 2</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="racaoQuantidade" class="form-label fw-bold">Quantidade (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="racaoQuantidade" name="racaoQuantidade" placeholder="0.00" required>
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

<div class="modal fade" id="modalEditarRacao" tabindex="-1" aria-labelledby="modalEditarRacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditarRacaoLabel"><i class="bi bi-pencil-square me-2"></i>Editar Registro de Ração</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="editarRacao.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="racaoLoteId" value="<?= $loteId ?>">
                    <input type="hidden" id="editarId" name="racaoId">
                    
                    <div class="mb-3">
                        <label for="editarData" class="form-label fw-bold">Data</label>
                        <input type="date" class="form-control" id="editarData" name="racaoData" required>
                    </div>
                    <div class="mb-3">
                        <label for="editarNF" class="form-label fw-bold">Nota Fiscal (NF)</label>
                        <input type="text" class="form-control" id="editarNF" name="racaoNf" required>
                    </div>
                    <div class="mb-3">
                        <label for="editarTipo" class="form-label fw-bold">Tipo</label>
                        <select class="form-select" id="editarTipo" name="racaoTipo" required>
                            <option value="Pré-Inicial">Pré-Inicial</option>
                            <option value="Inicial 1">Inicial 1</option>
                            <option value="Inicial 2">Inicial 2</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editarQuantidade" class="form-label fw-bold">Quantidade (kg)</label>
                        <input type="number" step="0.01" class="form-control" id="editarQuantidade" name="racaoQuantidade" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDeletarRacao" tabindex="-1" aria-labelledby="modalDeletarRacaoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalDeletarRacaoLabel"><i class="bi bi-exclamation-triangle me-2"></i>Excluir Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="excluirRacao.php" method="POST">
                <div class="modal-body text-center py-4">
                    <input type="hidden" name="racaoLoteId" value="<?=  $loteId ?>">
                    <input type="hidden" id="deletarId" name="racaoId">
                    
                    <i class="bi bi-x-circle text-danger display-4 mb-3 d-block"></i>
                    <p class="fs-5 m-0">Tem certeza que deseja apagar permanentemente este registro de ração?</p>
                    <small class="text-muted">Esta ação não poderá ser desfeita.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Exclusão</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Gatilho para o Modal de Edição
    const modalEditar = document.getElementById('modalEditarRacao');
    if (modalEditar) {
        modalEditar.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget; // Botão que disparou o modal
            
            // Extrai as informações dos atributos data-bs-*
            const id = button.getAttribute('data-bs-id');
            const data = button.getAttribute('data-bs-data');
            const nf = button.getAttribute('data-bs-nf');
            const tipo = button.getAttribute('data-bs-tipo');
            const quantidade = button.getAttribute('data-bs-quantidade');

            // Insere os valores extraídos dentro dos inputs correspondentes do formulário
            modalEditar.querySelector('#editarId').value = id;
            modalEditar.querySelector('#editarData').value = data;
            modalEditar.querySelector('#editarNF').value = nf;
            modalEditar.querySelector('#editarTipo').value = tipo;
            modalEditar.querySelector('#editarQuantidade').value = quantidade;
        });
    }

    // Gatilho para o Modal de Exclusão
    const modalDeletar = document.getElementById('modalDeletarRacao');
    if (modalDeletar) {
        modalDeletar.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-bs-id');
            
            // Insere o ID no input hidden do form de exclusão
            modalDeletar.querySelector('#deletarId').value = id;
        });
    }
</script>
</body>
</html>