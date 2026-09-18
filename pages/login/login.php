<?php 

session_start();

?> 

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SL | Gerenciador - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../../assets/SL_icone2.png">
    <style>
        body {
            background-color: #f8f9fa;
        }
        /* Estilização customizada baseada na imagem */
        .login-card {
            background-color: #006b3f; /* Verde escuro do fundo */
            color: white;
            max-width: 400px;
            width: 100%;
            border-radius: 0px; /* Altere para o valor desejado se quiser cantos arredondados */
        }
        .title-underline {
            border-bottom: 2px solid white;
            padding-bottom: 5px;
            font-style: italic;
        }
        .divider {
            border-top: 2px solid white;
            opacity: 1;
            margin: 20px 0;
        }
        .btn-login {
            background-color: white;
            color: #006b3f;
            font-weight: bold;
            font-size: 1.5rem;
            border: none;
            border-radius: 10px;
            padding: 10px 0;
            width: 100%;
            transition: background-color 0.2s;
        }
        .btn-login:hover {
            background-color: #e2e2e2;
            color: #006b3f;
        }
        .form-control-custom {
            border-radius: 20px;
            border: none;
            height: 38px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-load min-vh-100">

    <div class="login-card p-4 shadow">
        <div class="text-center mb-4">
            <h2 class="title-underline d-inline-block fw-bold px-2">SL | Gerenciador</h2>
        </div>

        <div class="text-center my-4">
            <img src="../../assets/porquinho.png" 
                 alt="Porquinho" 
                 class="img-fluid" 
                 style="max-height: 120px; filter: brightness(0) invert(1);"
        </div>

        <div class="divider"></div>

        <form method="POST" action="processarLogin.php">
            
            <div class="mb-4">

                <?php 

                if(isset($_SESSION['erro_login'])){
                    echo "<p class='text-danger'>".$_SESSION['erro_login'] . "</p>";
                    unset($_SESSION['erro_login']);
                }

                ?>

            </div>
            <div class="mb-4">
                <label for="produtorEmail" class="form-label fs-5 mb-1">Email:</label>
                <input type="email" class="form-control form-control-custom" name="produtorEmail" id="produtorEmail" required>
            </div>

            <div class="mb-4">
                <label for="produtorSenha" class="form-label fs-5 mb-1">Senha:</label>
                <input type="password" name="produtorSenha" id="produtorSenha" class="form-control form-control-custom">
            </div>

            <div class="mt-5 mb-2">
                <button type="submit" class="btn btn-login">Logar</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>