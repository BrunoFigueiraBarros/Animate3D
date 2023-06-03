<?php 
$host = 'localhost';
$usuario = 'u147978366_biblioteca_sis';
$senha = 'U3HkErMAHi^a';
$banco = 'u147978366_biblioteca';


// Criar objeto de conexão
$conexao = new Conexao($host, $usuario, $senha, $banco);
$conexao->conectar();
