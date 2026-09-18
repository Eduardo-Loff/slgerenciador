<?php 

// Dados da conexão [Servidor, Usuario do Banco, Senha e Nome do Banco]

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sl_gerenciador";

// Tenta tive de conexão

try {

    $conn = new PDO(
        "mysql:host=$servername;dbname=$dbname;charset=utf8",$username,$password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
// Tratamento de Erros
catch(PDOException $e){

    echo "Erro de conexão ". $e->getMessage();

    exit;

}