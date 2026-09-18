<?php

session_start();

require('../../config/conexao.php');

$produtorEmail = $_POST['produtorEmail'] ?? '';
$produtorSenha = $_POST['produtorSenha'] ?? '';

$sql = "SELECT * from PRODUTOR where produtorEmail = :produtorEmail LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":produtorEmail", $produtorEmail);
$stmt->execute();

if($stmt->rowCount() > 0){
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if(password_verify($produtorSenha, $dados['produtorSenha'])){
        $_SESSION['produtorId'] = $dados['produtorId'];
        $_SESSION['produtorNome'] = $dados['produtorNome'];
        $_SESSION['produtorEmail'] = $dados['produtorEmail'];
        $_SESSION['produtorSenha'] = $produtorSenha;
        header('Location: ../dashboard.php');
        exit(); 
    }else{
        $_SESSION['erro_login'] = "Senha incorreta!";
        header('Location: login.php');
        exit();
    }
}else{
    $_SESSION['erro_login'] = "Email nao cadastrado.";
    header('Location: login.php');
    exit();
}