<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';

class ProdutoModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    
    protected $conexao;

    protected $model = 'produto';

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function getProdutos()
    {
        $cmd = $this->conexao->query(
                "SELECT 
                    * 
                FROM
                    produto
                ORDER BY 
                    tipo
                ASC"
            );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);            
            return self::messageWithData(201, 'produtos encontrados', $dados);
        }

        return '';
    }

    public function prepareInsertProduto($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    descricao = :descricao, 
                    tipo = :tipo,
                    valor = :valor,
                    status = :status
                    "
                );

            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':descricao',$dados['descricao']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':status',$dados['status']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareUpdateProduto($dados, $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    descricao = :descricao, 
                    tipo = :tipo,
                    valor = :valor,
                    status = :status
                WHERE 
                    id = :id
                    "
                );

            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':descricao',$dados['descricao']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':status',$dados['status']);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "Dados Atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function buscaEntradaProduto()
    {
        $cmd = $this->conexao->query(
                "SELECT 
                    en.*,
                    p.descricao 
                FROM
                    entradaestoque en 
                INNER JOIN 
                    produto p
                ON 
                    p.id = en.produto_id
                ORDER BY 
                    p.descricao
                ASC"
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);            
            return self::messageWithData(201, 'entradas encontrados', $dados);
        }

        return '';
    }

    public function buscaProdutos()
    {
        $cmd = $this->conexao->query(
                "SELECT 
                    * 
                FROM
                    produto
                WHERE 
                    tipo = 'consumo'
                ORDER BY 
                    tipo
                ASC"
            );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);            
            return self::messageWithData(201, 'produtos encontrados', $dados);
        }

        return '';
    }

    public function buscaEstoques($request = null)
    {
        $cmd = $this->conexao->query(
                "SELECT 
                    e.*,
                    p.descricao 
                FROM
                    estoque e
                INNER JOIN 
                    produto p
                 ON 
                    p.id = e.produto_id
                WHERE 
                    p.descricao LIKE '%$request%'
                ORDER BY 
                    saldoAtual
                ASC"
            );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);            
            return self::messageWithData(201, 'produtos encontrados', $dados);
        }

        return self::messageWithData(200, 'não encontrados', []);
    }
    
    public function salvarEntrada($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    entradaestoque
                SET 
                    quantidade = :quantidade, 
                    fornecedor = :fornecedor,
                    valorCompra = :valor,
                    produto_id = :produto_id
                    "
                );

            $cmd->bindValue(':quantidade',$dados['quantidade']);
            $cmd->bindValue(':fornecedor',$dados['fornecedor']);
            $cmd->bindValue(':valor',$dados['valor']);
            $cmd->bindValue(':produto_id',$dados['produto_id']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "Dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function deleteEntrada($id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "DELETE FROM
                    entradaestoque
                WHERE 
                    id = :id
                    "
                );
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(200, "Dado deletado!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }
}