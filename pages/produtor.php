<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:login/login.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/SL_icone2.png">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        /* Customização da Barra Lateral */
        .sidebar {
            background-color: #006b3f;
            color: white;
        }
        
        /* Garante tela cheia na lateral apenas no desktop */
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

        /* Customização dos Cards Dinâmicos */
        .custom-card {
            background-color: #006b3f;
            color: white;
            border-radius: 20px;
            padding: 15px;
            border: none;
            transition: transform 0.2s;
        }
        .custom-card:hover {
            transform: scale(1.01);
        }
        .card-icon {
            font-size: 2.5rem;
            line-height: 1;
        }
        .btn-acessar {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            white-space: nowrap;
        }
        .btn-acessar:hover {
            text-decoration: underline;
            color: #e2e2e2;
        }

        /* Ajuste do botão fechar do modal para combinar com o tema */
        .btn-verde {
            background-color: #006b3f;
            color: white;
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
                    <p class="mb-1 text-white-50" style="font-size: 0.9rem;">Página Atual:</p>
                    <h5 class="fw-bold m-0">Produtor.</h5>
                </div>

                <ul class="nav flex-row flex-md-column gap-3 gap-md-2 justify-content-between justify-content-md-start">
                    <li class="nav-item">
                        <a class="nav-link p-0" href="dashboard.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="medicacao/medicacao.php">Medicações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold p-0" href="produtor.php">Produtor</a>
                    </li>
                    <li class="nav-item ms-auto ms-md-0">
                        <a href="login/logout.php" class="nav-link p-0 text-warning text-md-white">Sair</a>
                    </li>
                </ul>
            </nav>

            <main class="col-12 col-md-9 col-lg-10 p-3 p-md-5">
                <div class="d-flex flex-column gap-4 mx-auto" style="max-width: 900px;">
                    
                    <div class="card custom-card shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
                            <div class="d-flex align-items-center gap-3 gap-md-4">
                                <div class="card-icon px-1">
                                    <i class="bi bi-person-bounding-box"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">Nome Produtor:</h5>
                                </div>
                                <div class="ms-2 ms-md-4">
                                    <h5 class="mb-0 fw-normal text-white-50"><?= $_SESSION['produtorNome'] ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
                            <div class="d-flex align-items-center gap-3 gap-md-4">
                                <div class="card-icon px-1">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">Email Produtor:</h5>
                                </div>
                                <div class="ms-2 ms-md-4">
                                    <h5 class="mb-0 fw-normal text-white-50"><?= $_SESSION['produtorEmail'] ?></h5>
                                </div>
                            </div>
                            <div class="ms-auto ms-sm-0">
                                <button type="button" class="btn btn-link btn-acessar fw-normal p-0 border-0 v-align-baseline" data-bs-toggle="modal" data-bs-target="#modalEditarEmail">
                                    Editar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
                            <div class="d-flex align-items-center gap-3 gap-md-4">
                                <div class="card-icon px-1">
                                    <i class="bi bi-unlock"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">Senha Atual:</h5>
                                </div>
                                <div class="ms-2 ms-md-4">
                                    <h5 class="mb-0 fw-normal text-white-50">••••••••</h5>
                                </div>
                            </div>
                            <div class="ms-auto ms-sm-0">
                                <button type="button" class="btn btn-link btn-acessar fw-normal p-0 border-0 v-align-baseline" data-bs-toggle="modal" data-bs-target="#modalEditarSenha">
                                    Editar
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <div class="modal fade" id="modalEditarEmail" tabindex="-1" aria-labelledby="modalEmailLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark" id="modalEmailLabel">Alterar E-mail do Produtor</h5>
                    <button type="button" class="btn-close" data-bs-dimensions data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="atualizar_produtor.php" method="POST">
                    <div class="modal-body text-dark">
                        <input type="hidden" name="acao" value="atualizar_email">
                        <div class="mb-3">
                            <label for="novoEmail" class="form-label fw-semibold">Novo E-mail:</label>
                            <input type="email" class="form-control" id="novoEmail" name="novoEmail" value="<?= $_SESSION['produtorEmail'] ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarSenha" tabindex="-1" aria-labelledby="modalSenhaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark" id="modalSenhaLabel">Alterar Senha de Acesso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="atualizarProdutor.php" method="POST">
                    <div class="modal-body text-dark">
                        <input type="hidden" name="acao" value="atualizar_senha">
                        <div class="mb-3">
                            <label for="senhaAtual" class="form-label fw-semibold">Senha Atual:</label>
                            <input type="password" class="form-control" id="senhaAtual" name="senhaAtual" required>
                        </div>
                        <div class="mb-3">
                            <label for="novaSenha" class="form-label fw-semibold">Nova Senha:</label>
                            <input type="password" class="form-control" id="novaSenha" name="novaSenha" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-verde">Salvar Nova Senha</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>