<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

if(isset($_POST['propriedadeId'])){

    $propriedadeId = $_POST['propriedadeId'];

}elseif(isset($_SESSION['propriedadeIdAtual'])){

    $propriedadeId = $_SESSION['propriedadeIdAtual'];

}else{

    header('Location:../dashboard.php');
    exit;

}

require("../../config/conexao.php");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Lotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/SL_icone2.png">    
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Customização da Barra Lateral */
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
        /* Componentes Verdes Customizados */
        .btn-verde {
            background-color: #006b3f;
            color: white;
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 500;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-verde:hover {
            background-color: #00522e;
            color: white;
        }
        .custom-select {
            border: 2px solid #006b3f;
            border-radius: 20px;
            padding: 12px 20px;
            font-size: 1.1rem;
            color: #006b3f;
            font-weight: 500;
        }
        /* Card do Lote */
        .card-lote {
            background-color: #006b3f;
            color: white;
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }
        .card-lote-header {
            border-bottom: 2px solid white;
            padding: 15px 25px;
        }
        .card-lote-body {
            padding: 25px;
            font-size: 1.1rem;
            line-height: 2;
        }
        /* Botões internos do Card */
        .btn-card-outline {
            background-color: transparent;
            color: white;
            border: 2px solid white;
            border-radius: 12px;
            padding: 6px 20px;
            width: 100%;
            transition: background-color 0.2s;
        }
        .btn-card-outline:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        .btn-card-danger {
            background-color: #ff3333;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 8px 20px;
            width: 100%;
            transition: opacity 0.2s;
        }
        .btn-card-danger:hover {
            opacity: 0.9;
            color: white;
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
                    <h5 class="fw-bold m-0">Lotes</h5>
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
                <div class="mx-auto" style="max-width: 900px;">
                    
                    <?php 
                    
                        try{
                            $sql = "SELECT * FROM lote WHERE lotePropriedadeId = $propriedadeId ORDER BY loteCriacao DESC";
                            $stm = $conn->prepare($sql);
                            $stm->execute();
                            $rows = $stm->fetchAll(PDO::FETCH_OBJ);
                        
                    
                    ?>

                    <div class="mb-4">
                        <select id="selectLote" class="form-select custom-select shadow-sm">
                            <option value="" selected disabled>Selecione um lote...</option>
                            <?php if(count($rows) > 0): ?>

                            <?php foreach($rows as $r): ?>
                            <option value="<?= $r->loteId ?>" data-codigo="<?= $r->loteCodigo ?>" data-criacao="<?= $r->loteCriacao ?>" data-status="<?= $r->loteEstado ?>" data-obs="<?= $r->loteObservacoes ?>"><?= $r->loteCodigo ?></option>
                            <?php endforeach ?>
                            <?php else: ?>
                            <option value="">Não Existem Lotes Nessa Popriedade.</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <?php

                    } catch (PDOException $e) {

                    // captura erros do banco
                    echo '<div class="alert alert-danger">
                            Erro: ' . $e->getMessage() . '
                        </div>';
                    }
                    ?>

                    <div class="text-end mb-5">
                        <button type="button" class="btn btn-verde shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCriarLote">
                            Criar Novo Lote
                        </button>
                    </div>

                    <div id="cardLoteContainer" class="card card-lote shadow d-none">
                        <div class="card-lote-header d-flex align-items-center">
                            <i class="bi bi-piggy-bank fs-2 me-3"></i>
                            <h2 class="m-0 fw-bold" id="displayNomeLote"></h2>
                        </div>
                        <div class="card-lote-body">
                            <div class="row gy-3">
                                <div class="col-12 col-md-8">
                                    <p class="m-0"><strong>Código do Lote:</strong> <span id="displayCodigo"></span></p>
                                    <p class="m-0"><strong>Data de Criação:</strong> <span id="displayCriacao"></span></p>
                                    <p class="m-0"><strong>Status do Lote:</strong> <span id="displayStatus"></span></p>
                                    <p class="m-0"><strong>Observações:</strong> <span id="displayObservacoes"></span></p>
                                </div>
                                <div class="col-12 col-md-4 d-flex flex-column gap-2 justify-content-center">
                                    <button type="button" class="btn btn-card-outline" data-bs-toggle="modal" data-bs-target="#modalEditarLote" id="btnEditarTrigger">
                                        Editar Lote
                                    </button>
                                    <button type="button" class="btn btn-card-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmarExclusao" id="btnExcluirTrigger">
                                        Excluir Lote
                                    </button>
                                    <form action="loteView.php" method="post" style="display:inline">
                                        <input type="hidden" id="acessarLoteId" name="loteId" value="">    

                                        <button type="submit" class="btn btn-card-outline">
                                            Acessar Lote
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <div class="modal fade" id="modalCriarLote" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Criar Novo Lote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="criarLote.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="acao" value="inserir">
                        <div class="mb-3">
                            <input type="hidden" class="form-control" name="lotePropriedadeId" required value="<?= $propriedadeId ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Código do Lote:</label>
                            <input type="text" class="form-control" name="loteCodigo" required placeholder="Ex: 001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Observações:</label>
                            <textarea class="form-control" name="loteObs" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Lote Criação:</label>
                            <input type="date" class="form-control"  name="loteCriacao" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde">Salvar Lote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarLote" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Editar Lote</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="editarLote.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="propriedadeId", value="<?=  $propriedadeId ?>">
                        <input type="hidden" id="editId" name="loteId">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Código do Lote:</label>
                            <input type="text" class="form-control" id="editCodigo" name="loteCodigo" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Data de Criação:</label>
                            <input type="date" class="form-control" id="editCriacao" name="loteCriacao" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Observações:</label>
                            <textarea class="form-control" id="editObs" name="loteObs" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde">Atualizar Dados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalConfirmarExclusao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="excluirLote.php" method="POST">
                    <div class="modal-body text-center p-4">
                        <input type="hidden" name="propriedadeId" value="<?=  $propriedadeId ?>">
                        <input type="hidden" id="deleteId" name="loteId">
                        <p class="fs-5 mb-1">Tem certeza que deseja excluir o lote <strong id="deleteCodigoLote"></strong>?</p>
                        <span class="text-danger small">Esta ação não poderá ser desfeita.</span>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Sim, Excluir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const selectLote = document.getElementById('selectLote');
        const cardContainer = document.getElementById('cardLoteContainer');
        
        const displayNomeLote = document.getElementById('displayNomeLote');
        const displayCodigo = document.getElementById('displayCodigo');
        const displayCriacao = document.getElementById('displayCriacao');
        const displayStatus = document.getElementById('displayStatus');
        const displayObservacoes = document.getElementById('displayObservacoes');

        selectLote.addEventListener('change', function() {
            const opcaoSelecionada = this.options[this.selectedIndex];
            
            if (opcaoSelecionada.value !== "") {
              
                const codigo = opcaoSelecionada.getAttribute('data-codigo');
                const criacao = opcaoSelecionada.getAttribute('data-criacao');
                const status = opcaoSelecionada.getAttribute('data-status');
                const obs = opcaoSelecionada.getAttribute('data-obs');
                const id = opcaoSelecionada.value; 


                displayNomeLote.innerText = "Lote: " + codigo;
                displayCodigo.innerText = codigo;
                displayCriacao.innerText = criacao;
                displayStatus.innerText = status;
                displayObservacoes.innerText = obs;

                document.getElementById('acessarLoteId').value = id;

                document.getElementById('editId').value = id;
                document.getElementById('editCodigo').value = codigo;
                document.getElementById('editCriacao').value = criacao;
                document.getElementById('editObs').value = obs;

                document.getElementById('deleteId').value = id;
                document.getElementById('deleteCodigoLote').innerText = codigo;

                cardContainer.classList.remove('d-none');
            } else {
                cardContainer.classList.add('d-none');
            }
        });
    </script>
</body>
</html>