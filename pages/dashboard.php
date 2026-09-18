<?php 

session_start();

if(!isset($_SESSION['produtorId'])){
    header('Location:login/login.php');
    exit;
}

require('../config/conexao.php');

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
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">

            <nav class="col-12 col-md-3 col-lg-2 sidebar p-4 d-flex flex-column">
                <h3 class="title-underline fw-bold mb-3 mb-md-4">SL | Gerenciador</h3>
                
                <div class="mb-3 mb-md-5">
                    <p class="mb-1 text-white-50" style="font-size: 0.9rem;">Bem-vindo,</p>
                    <h5 class="fw-bold m-0"><?= $_SESSION['produtorNome']; ?>.</h5>
                </div>

                <ul class="nav flex-row flex-md-column gap-3 gap-md-2 justify-content-between justify-content-md-start">
                    <li class="nav-item">
                        <a class="nav-link fw-bold p-0" href="dashboard.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="medicacao/medicacao.php">Medicações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-0" href="produtor.php">Produtor</a>
                    </li>
                    <li class="nav-item ms-auto ms-md-0">
                        <a href="login/logout.php" class="nav-link p-0 text-warning text-md-white">Sair</a>
                    </li>
                </ul>
            </nav>

            <main class="col-12 col-md-9 col-lg-10 p-3 p-md-5">

            <?php 
            
                try{
                        $sql = "SELECT * FROM propriedade WHERE propriedadeProdutorId = :produtorId";
                        $stm = $conn->prepare($sql);
                        $stm->execute([
                            ':produtorId' => $_SESSION['produtorId']
                        ]);
                        $rows = $stm->fetchAll(PDO::FETCH_OBJ);
            ?>
                <div class="d-flex flex-column gap-4 mx-auto" style="max-width: 900px;">
                   
                    <?php if(count($rows)>0): ?>

                    <?php foreach($rows as $r): ?>

                    <div class="card custom-card shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
                            <div class="d-flex align-items-center gap-3 gap-md-4">
                                <div class="card-icon px-1">
                                    <i class="bi bi-piggy-bank"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal"><?= htmlspecialchars($r->propriedadeNome) ?></h5>
                                </div>
                                <div class="ms-2 ms-md-4">
                                    <h5 class="mb-0 fw-normal text-white-50"><?= htmlspecialchars($r->propriedadeTipo) ?></h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-4 ms-auto ms-sm-0">
                                <span class="fs-6 fs-md-5"><?= (new DateTime($r->propriedadeDataCriacao))->format('d/m/Y') ?></span>
                                <form action="lote/lotes.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="propriedadeId" value="<?= htmlspecialchars($r->propriedadeId) ?>">
                                    
                                    <button type="submit" class="btn btn-link p-0 text-decoration-none text-light fw-semibold">
                                        Ver Lotes
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <?php endforeach; ?>
                    <?php else: ?>
                        <h5>Esse produtor não possui propriedades</h5>
                    <?php endif; ?>

                    <?php

                    } catch (PDOException $e) {

                    // captura erros do banco
                    echo '<div class="alert alert-danger">
                            Erro: ' . $e->getMessage() . '
                        </div>';
                    }
                    ?>

                    <div class="card custom-card shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
                            <div class="d-flex align-items-center gap-3 gap-md-4">
                                <div class="card-icon px-1 d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-syringe" viewBox="0 0 16 16" style="transform: rotate(-45deg);">
                                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                        <path d="M4.5 3a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5zM11 4v1H5V4zM5 6h6v1H5zm6 2V7H5v1zm-6 1h6v1H5zm6 2V10H5v1z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">Medicações</h5>
                                </div>
                            </div>
                            <div class="ms-auto ms-sm-0">
                                <a href="medicacao/medicacao.php" class="btn-acessar fw-normal">Acessar</a>
                            </div>
                        </div>
                    </div>

                    <div class="card custom-card shadow-sm">
                        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3 p-2">
                            <div class="d-flex align-items-center gap-3 gap-md-4">
                                <div class="card-icon px-1">
                                    <i class="bi bi-person fs-1"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-normal">Produtor</h5>
                                </div>
                            </div>
                            <div class="ms-auto ms-sm-0">
                                <a href="produtor.php" class="btn-acessar fw-normal">Acessar</a>
                            </div>
                        </div>
                    </div>

                </div>
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>