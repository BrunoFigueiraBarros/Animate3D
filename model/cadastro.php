<?php

class Cadastro {
    private $conexao;

    public function __construct($conexao) {
        $this->conexao = $conexao;
    }

    public function cadastrarDados($color3d, $backgroundColor, $resolution, $objectSize, $animationSpeed) {
        try {
            $stmt = $this->conexao->prepare("INSERT INTO ts_controle (color3d, backgroundColor, resolution, objectSize, animationSpeed) 
                                            VALUES (?, ?, ?, ?, ?)");

            $stmt->bindParam(1, $color3d);
            $stmt->bindParam(2, $backgroundColor);
            $stmt->bindParam(3, $resolution);
            $stmt->bindParam(4, $objectSize);
            $stmt->bindParam(5, $animationSpeed);

            $stmt->execute();
            return true; // Cadastro bem-sucedido
        } catch (PDOException $e) {
            return false; // Erro no cadastro
        }
    }
}

?>
