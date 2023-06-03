<?php

require_once 'model/conexao.php';
require_once 'model/cadastro.php';
require_once 'config/config.php';





// Criar objeto de cadastro
$cadastro = new Cadastro($conexao->getConexao());

// Exemplo de dados fornecidos
$color3d = $_POST['color3d'];
$backgroundColor = $_POST['backgroundColor'];
$resolution = $_POST['resolution'];
$objectSize = $_POST['objectSize'];
$animationSpeed = $_POST['animationSpeed'];

// Realizar o cadastro dos dados
if ($cadastro->cadastrarDados($color3d, $backgroundColor, $resolution, $objectSize, $animationSpeed)) {
    echo "Dados cadastrados com sucesso!";
} else {
    echo "Erro no cadastro dos dados.";
}

?>
