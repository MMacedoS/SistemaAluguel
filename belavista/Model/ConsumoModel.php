<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';

class ConsumoModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    
    protected $conexao;
    protected $produtoModel;

    protected $model = 'consumo';

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
        $this->materialModel = new MaterialModel();
    }

    public function inserirConsumo($dados, $reserva_id)
    {
        $produto_id = $dados['produto'];
        $quantidade =  $dados['quantidade'];

        $produto = $this->materialModel->findById($produto_id)['data'];
        
        if(empty($produto)) {
            return self::message(422, "produto não encontrado");
        }

        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    quantidade = :quantidade, 
                    descricao = :descricao, 
                    valor = :valor,
                    reserva_id = :reserva_id,
                    material_id = :material_id,
                    funcionario = :funcionario,
                    tipo = :tipo
                    "
                );

            $cmd->bindValue(':quantidade',$quantidade);
            $cmd->bindValue(':descricao',$produto[0]['nome']);
            $cmd->bindValue(':valor',$produto['0']['valor']);
            $cmd->bindValue(':tipo',$produto['0']['tipo']);
            $cmd->bindValue(':reserva_id',$reserva_id);
            $cmd->bindValue(':material_id',$produto_id);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function insertDiaria($dados, $data)
    {
        $produto_id = 1;
        $quantidade =  1;
        $valor = $dados['valor'];
        $reserva_id = $dados['id'];

        $produto = $this->produtoModel->findById($produto_id)['data'];
        
        if(empty($produto)) {
            return self::message(422, "produto não encontrado");
        }

        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    quantidade = :quantidade, 
                    descricao = :descricao, 
                    valorUnitario = :valor_unitario,
                    reserva_id = :reserva_id,
                    produto_id = :produto_id,
                    funcionario = :funcionario,
                    created_at = :created_at
                    "
                );

            $cmd->bindValue(':quantidade',$quantidade);
            $cmd->bindValue(':descricao',$produto[0]['descricao']);
            $cmd->bindValue(':valor_unitario',$valor);
            $cmd->bindValue(':reserva_id',$reserva_id);
            $cmd->bindValue(':produto_id',$produto_id);
            $cmd->bindValue(':created_at',$data);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function getDadosConsumos($id){
        $cmd  = $this->conexao->query(
            "SELECT 
                    *
                FROM 
                    $this->model
                WHERE 
                    reserva_id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::messageWithData(201, 'consumo encontrados', $dados);
        }

        return self::messageWithData(201, 'nenhum dado encontrado', []);
    }

    public function getRemoveConsumo($id){
        $cmd  = $this->conexao->query(
            "DELETE 
                FROM 
                $this->model
                WHERE
                    id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::message(200, 'consumo deletado');
        }

        return self::message(422, 'nehum dado encontrado');
    }
}