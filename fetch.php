<?php

require_once 'model/conexao.php';
require_once 'model/ConsultaDados.php';
require_once 'config/config.php';


// Criação da instância de conexão
$conexao = new Conexao($host, $usuario, $senha, $banco);
$conexao->conectar();

// Criação da instância de ConsultaDados
$consultaDados = new ConsultaDados($conexao);

// Obter os dados do banco de dados
$resultados = $consultaDados->obterDados();

// Retornar os dados como resposta em formato JSON
header('Content-Type: application/json');
if ($resultados) {
    echo json_encode(['success' => true, 'data' => $resultados]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao obter dados do banco de dados.']);
}

?>
