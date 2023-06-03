<?php

class Conexao {
    private $host;
    private $usuario;
    private $senha;
    private $banco;
    private $conexao;

    public function __construct($host, $usuario, $senha, $banco) {
        $this->host = $host;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->banco = $banco;
    }

    public function conectar() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->banco};charset=utf8mb4";
            $this->conexao = new PDO($dsn, $this->usuario, $this->senha);
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public function getConexao() {
        return $this->conexao;
    }
}

?>
