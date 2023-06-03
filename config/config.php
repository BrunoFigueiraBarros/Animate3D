<?php 
$host = 'localhost';
$usuario = '';
$senha = '';
$banco = '';


// Criar objeto de conexão
$conexao = new Conexao($host, $usuario, $senha, $banco);
$conexao->conectar();
