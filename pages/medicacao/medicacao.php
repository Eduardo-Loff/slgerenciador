<?php 
session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require("../../config/conexao.php");

try {
    $sql = "SELECT * FROM medicacao ORDER BY medicacaoNome ASC";
    $stm =$conn->prepare($sql);$stm->execute();
    $rows =$stm->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $erroBanco =$e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Medicações</title>
    
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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

        /* Estilização Customizada da Tabela */
        .table-verde thead th {
            background-color: #006b3f;
            color: white;
            font-weight: normal;
            font-size: 1.1rem;
            padding: 12px;
            border: 1px solid #000;
        }
        .table-verde tbody td {
            background-color: white;
            padding: 12px;
            border: 1px solid #000;
            vertical-align: middle;
        }

        /* Botão Verde Customizado */
        .btn-verde {
            background-color: #006b3f;
            color: white;
            border-radius: 15px;
            padding: 10px 25px;
            font-size: 1.1rem;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-verde:hover {
            background-color: #00522e;
            color: white;
        }

        /* Overlay de Carregamento Estilo Ampulheta */
        #overlayCarregamento {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .spinner-box {
            background: #fff;
            padding: 40px 50px;
            border-radius: 10px;
            text-align: center;
        }
        .spinner-relogio {
            font-size: 60px;
            color: #006b3f;
            animation: girar 1s linear infinite;
        }
        @keyframes girar {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <nav class="col-12 col-md-3 col-lg-2 sidebar p-4 d-flex flex-column">
                <h3 class="title-underline fw-bold mb-3 mb-md-4">SL | Gerenciador</h3>
                
                <div class="mb-3 mb-md-5">
                    <p class="mb-1 text-white-50" style="font-size: 0.9rem;">Tela Atual:</p>
                    <h5 class="fw-bold m-0">Medicações</h5>
                </div>

                <ul class="nav flex-row flex-md-column gap-3 gap-md-2 justify-content-between justify-content-md-start">
                    <li class="nav-item">
                        <a class="nav-link p-0" href="../dashboard.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold p-0" href="medicacoes.php">Medicações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="../produtor.php">Produtor</a>
                    </li>
                    <li class="nav-item ms-auto ms-md-0">
                        <a href="../login/logout.php" class="nav-link p-0 text-warning text-md-white">Sair</a>
                    </li>
                </ul>
            </nav>

            <!-- Conteúdo Principal -->
            <main class="col-12 col-md-9 col-lg-10 p-3 p-md-5">
                <div class="mx-auto" style="max-width: 1000px;">

                    <!-- Container para mensagens de sucesso/erro via AJAX -->
                    <div id="mensagemRetorno">
                        <?php if (isset($erroBanco)): ?>
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i> Erro ao carregar dados: <?= htmlspecialchars($erroBanco) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive shadow-sm mb-4">
                        <table class="table table-verde text-center m-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 35%;">Nome</th>
                                    <th scope="col" style="width: 35%;">Tipo</th>
                                    <th scope="col" style="width: 30%;">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="corpoTabela">
                                <?php if (!empty($rows) && count($rows) > 0): ?>
                                    <?php foreach($rows as$r): ?>
                                        <tr id="linha-<?= $r->medicacaoId ?>">
                                            <td><?= htmlspecialchars($r->medicacaoNome) ?></td>
                                            <td><?= htmlspecialchars($r->medicacaoTipo) ?></td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-link p-0 text-decoration-none text-dark fw-semibold btn-editar" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#modalEditarMedicacao"
                                                        data-id="<?= htmlspecialchars($r->medicacaoId) ?>"
                                                        data-nome="<?= htmlspecialchars($r->medicacaoNome) ?>"
                                                        data-tipo="<?= htmlspecialchars($r->medicacaoTipo) ?>">
                                                    Editar
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr id="semMedicamentos">
                                        <td colspan="3">Não foram encontrados medicamentos.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-verde shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarMedicacao">
                            <i class="fa fa-plus"></i> Adicionar Novo
                        </button>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <!-- Modal Cadastrar -->
    <div class="modal fade" id="modalAdicionarMedicacao" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark" id="modalAddLabel">Cadastrar Nova Medicação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formAdicionarMedicacao" action="criarMedicamento.php" method="POST">
                    <div class="modal-body text-dark text-start">
                        <input type="hidden" name="acao" value="inserir">
                        
                        <div class="mb-3">
                            <label for="medicacaoNome" class="form-label fw-semibold">Nome da Medicação:</label>
                            <input type="text" class="form-control" id="medicacaoNome" name="medicacaoNome" required placeholder="Ex: Penicilina">
                        </div>
                        <div class="mb-3">
                            <label for="medicacaoTipo" class="form-label fw-semibold">Tipo:</label>
                            <select name="medicacaoTipo" id="medicacaoTipo" class="form-select custom-select shadow-sm" required>
                                <option value="" selected disabled>Selecione...</option>
                                <option value="Injetavel">Injetavel</option>
                                <option value="Oral">Oral Via Agua</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde" id="btnSalvarCadastro">Salvar Medicação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditarMedicacao" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark" id="modalEditLabel">Editar Dados da Medicação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditarMedicacao" action="editarMedicacao.php" method="POST">
                    <div class="modal-body text-dark text-start">
                        <input type="hidden" name="acao" value="atualizar">
                        <input type="hidden" id="editId" name="editId">
                        
                        <div class="mb-3">
                            <label for="editNome" class="form-label fw-semibold">Nome da Medicação:</label>
                            <input type="text" class="form-control" id="editNome" name="editNome" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTipo" class="form-label fw-semibold">Tipo:</label>
                            <select class="form-select custom-select shadow-sm" id="editTipo" name="editTipo" required>
                                <option value="Injetavel">Injetavel</option>
                                <option value="Oral">Oral Via Agua</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde" id="btnSalvarEdicao">Atualizar Dados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Overlay de Carregamento -->
    <div id="overlayCarregamento">
        <div class="spinner-box">
            <div class="spinner-relogio"><i class="fa fa-hourglass-half"></i></div>
            <h4 class="mt-3" id="textoOverlay">Processando...</h4>
            <p class="text-muted">Aguarde um momento</p>
        </div>
    </div>

    <!-- Scripts JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {

        // 1. Passagem de dados para o Modal de Edição
        $('#modalEditarMedicacao').on('show.bs.modal', function(event) {
            var botao = $(event.relatedTarget);
            var id = botao.data('id');
            var nome = botao.data('nome');
            var tipo = botao.data('tipo');

            $('#editId').val(id);
            $('#editNome').val(nome);
            $('#editTipo').val(tipo);
        });

        // 2. Submit via AJAX - Inserir Medicação
        $('#formAdicionarMedicacao').on('submit', function(e) {
            e.preventDefault();

            $('#textoOverlay').text('Cadastrando medicação...');
            $('#overlayCarregamento').css('display', 'flex').hide().fadeIn(300);
            $('#btnSalvarCadastro').prop('disabled', true);

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json'
            })
            .done(function(resposta) {
                if (resposta.status === 'sucesso') {
                    $('#modalAdicionarMedicacao').modal('hide');
                    $('#formAdicionarMedicacao')[0].reset();

                    $('#mensagemRetorno').html(`
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fa fa-check-circle"></i> ${resposta.mensagem}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                    // Recarrega a página após 1 segundo para atualizar os dados, ou você pode reordenar dinamicamente
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    $('#mensagemRetorno').html(`
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i> ${resposta.mensagem}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }
            })
            .fail(function() {
                $('#mensagemRetorno').html(`
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fa fa-exclamation-triangle"></i> Erro na comunicação com o servidor.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            })
            .always(function() {
                $('#overlayCarregamento').fadeOut(300);
                $('#btnSalvarCadastro').prop('disabled', false);
            });
        });

        // 3. Submit via AJAX - Editar Medicação
        $('#formEditarMedicacao').on('submit', function(e) {
            e.preventDefault();

            $('#textoOverlay').text('Atualizando medicação...');
            $('#overlayCarregamento').css('display', 'flex').hide().fadeIn(300);
            $('#btnSalvarEdicao').prop('disabled', true);

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json'
            })
            .done(function(resposta) {
                if (resposta.status === 'sucesso') {
                    $('#modalEditarMedicacao').modal('hide');

                    $('#mensagemRetorno').html(`
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fa fa-check-circle"></i> ${resposta.mensagem}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    $('#mensagemRetorno').html(`
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-exclamation-triangle"></i> ${resposta.mensagem}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);
                }
            })
            .fail(function() {
                $('#mensagemRetorno').html(`
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fa fa-exclamation-triangle"></i> Erro na comunicação com o servidor.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            })
            .always(function() {
                $('#overlayCarregamento').fadeOut(300);
                $('#btnSalvarEdicao').prop('disabled', false);
            });
        });

    });
    </script>
</body>
</html>