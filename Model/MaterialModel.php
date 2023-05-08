<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';

class MaterialModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    
    protected $conexao;

    protected $model = 'material';

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function prepareInsertMaterial($dados)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){
            
            // if($this->verificaMaterialSeExiste($dados))
            // {   
                return $this->insertMaterial($dados); 
            // }

        }

        return $validation;
    }

    private function verificaMaterialSeExiste($dados)
    {
        $apartamento = (int)$dados['material'];

        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM
                $this->model
            WHERE
                numero = $apartamento"
        );

        if($cmd->rowCount()>0)
        {
            return false;
        }

        return true;
    }

    private function insertMaterial($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    nome = :nome, 
                    descricao = :descricao, 
                    tipo = :tipo,
                    aluguel = :aluguel,
                    valor = :valor"
                );

            $cmd->bindValue(':nome',$dados['nome']);
            $cmd->bindValue(':descricao',$dados['descricao']);
            $cmd->bindValue(':tipo',$dados['tipo']);
            $cmd->bindValue(':aluguel',$dados['aluguel']);
            $cmd->bindValue(':valor',$dados['valor']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareUpdateMaterial($dados, $id)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){            
            return $this->updateMaterial($dados, $id); 
        }

        return $validation;
    }

    private function updateMaterial($dados, int $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    nome = :nome, 
                    descricao = :descricao, 
                    tipo = :tipo,
                    aluguel = :aluguel,
                    valor = :valor
                WHERE 
                    id = :id"
                );

                $cmd->bindValue(':nome',$dados['nome']);
                $cmd->bindValue(':descricao',$dados['descricao']);
                $cmd->bindValue(':tipo',$dados['tipo']);
                $cmd->bindValue(':aluguel',$dados['aluguel']);
                $cmd->bindValue(':valor',$dados['valor']);
                $cmd->bindValue(':id',$id);
                $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados Atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function findMaterial($request)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                * 
            FROM
                $this->model
            WHERE 
                nome
            LIKE
                '%$request%'"
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
        
    }

    public function prepareChangedMaterial($id)
    {
        $apartamento = self::findById($id);

        if(is_null($apartamento)) {
            return self::messageWithData(422, 'apartamento não encontrado', []);
        }

        return $this->updateStatusMaterial(1, $id);
    }

    private function updateStatusMaterial($status, $id)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    status = :status
                WHERE 
                    id = :id"
                );
            $cmd->bindValue(':status',$status);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareChangedMaterialStatus($id, $status)
    {
        $apartamento = self::findById($id);

        if(is_null($apartamento)) {
            return self::messageWithData(422, 'apartamento não encontrado', []);
        }

        return $this->updateStatusMaterial($status, $id);
    }


    public function getMaterial()
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                * 
            FROM
                $this->model "
        );

        if($cmd->rowCount() > 0)
        {
            return self::messageWithData(200, "dados encontrados", $cmd->fetchAll(PDO::FETCH_ASSOC));
        }

        return self::messageWithData(422, "sem dados",[]);
    }
}