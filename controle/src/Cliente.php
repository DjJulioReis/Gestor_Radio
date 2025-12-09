<?php

class Cliente
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Busca todos os clientes do banco de dados, incluindo o nome do plano.
     *
     * @return array Uma lista de clientes.
     */
    public function getAll()
    {
        $sql = "SELECT c.id, c.empresa, c.cnpj_cpf, c.email, c.telefone,
                       c.created_at AS data_cadastro, c.plano_id, c.ativo,
                       p.nome AS plano
                FROM clientes c
                LEFT JOIN planos p ON c.plano_id = p.id
                ORDER BY c.empresa";

        $result = $this->conn->query($sql);

        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
}
