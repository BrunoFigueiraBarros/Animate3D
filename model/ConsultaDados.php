<?php

class ConsultaDados {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function obterDados() {
        try {
            $stmt = $this->conexao->getConexao()->query('SELECT * FROM ts_controle ORDER BY id_controle DESC LIMIT 1');
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false; // Erro na consulta
        }
    }
}