<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:../login/login.php');
    exit;
}

require("../../config/conexao.php");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Medicações</title>
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
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">

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

            <main class="col-12 col-md-9 col-lg-10 p-3 p-md-5">
                <div class="mx-auto" style="max-width: 1000px;">

                    <?php 

                        try{

                            $sql = "SELECT * FROM medicacao";
                            $stm = $conn->prepare($sql);
                            $stm->execute();
                            $rows = $stm->fetchAll(PDO::FETCH_OBJ);

                        
                    
                    ?>
                    
                    <div class="table-responsive shadow-sm mb-4">
                        <table class="table table-verde text-center m-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 35%;">Nome</th>
                                    <th scope="col" style="width: 35%;">Tipo</th>
                                    <th scope="col" style="width: 30%;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if(count($rows) > 0): ?>

                                <?php foreach($rows as $r): ?>
                                <tr>
                                    <td style="display: none;"><?= htmlspecialchars($r->medicacaoId) ?></td>
                                    <td><?= htmlspecialchars($r->medicacaoNome) ?></td>
                                    <td><?= htmlspecialchars($r->medicacaoTipo) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-link p-0 text-decoration-none text-dark fw-semibold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarMedicacao"
                                                data-id="<?= htmlspecialchars($r->medicacaoId) ?>"
                                                data-nome="<?= htmlspecialchars($r->medicacaoNome) ?>"
                                                data-tipo="<?= htmlspecialchars($r->medicacaoTipo) ?>"
                                            >Editar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td> Não foram encontrados medicamentos </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-verde shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAdicionarMedicacao">
                            Adicionar Novo
                        </button>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <?php

    } catch (PDOException $e) {

    // captura erros do banco
    echo '<div class="alert alert-danger">
            Erro: ' . $e->getMessage() . '
          </div>';
    }
    ?>

    <div class="modal fade" id="modalAdicionarMedicacao" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark" id="modalAddLabel">Cadastrar Nova Medicação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="criarMedicamento.php" method="POST">
                    <div class="modal-body text-dark text-start">
                        <input type="hidden" name="acao" value="inserir">
                        
                        <div class="mb-3">
                            <label for="medicacaoNome" class="form-label fw-semibold">Nome da Medicação:</label>
                            <input type="text" class="form-control" id="medicacaoNome" name="medicacaoNome" required placeholder="Ex: Penicilina">
                        </div>
                        <div class="mb-3">
                            <label for="medicacaoTipo" class="form-label fw-semibold">Tipo:</label>
                            <select name="medicacaoTipo" id="medicacaoTipo" class="form-select custom-select shadow-sm" required>
                            <option value="" select disabled>Selecione...</option>
                            <option value="Injetavel">Injetavel</option>
                            <option value="Oral">Oral Via Agua</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde">Salvar Medicação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarMedicacao" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark" id="modalEditLabel">Editar Dados da Medicação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="editarMedicacao.php" method="POST">
                    <div class="modal-body text-dark text-start">
                        <input type="hidden" name="acao" value="atualizar">
                        <input type="hidden" id="editId" name="id">
                        
                        <div class="mb-3">
                            <label for="editNome" class="form-label fw-semibold">Nome da Medicação:</label>
                            <input type="text" class="form-control" id="editNome" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="editTipo" class="form-label fw-semibold">Tipo:</label>
                            <select  class="form-select custom-select shadow-sm" id="editTipo" name="tipo" required>
                            <option value="Injetavel">Injetavel</option>
                            <option value="Oral">Oral Via Agua</option>
                            </select>
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

    <script>
        const modalEditar = document.getElementById('modalEditarMedicacao');
        if (modalEditar) {
            modalEditar.addEventListener('show.bs.modal', event => {
                // Botão que disparou o modal
                const botao = event.relatedTarget;
                
                // Extrai as informações dos atributos data-* do botão
                const id = botao.getAttribute('data-id');
                const nome = botao.getAttribute('data-nome');
                const tipo = botao.getAttribute('data-tipo');

                // Atualiza os inputs internos do modal de edição
                document.getElementById('editId').value = id;
                document.getElementById('editNome').value = nome;
                document.getElementById('editTipo').value = tipo;
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>