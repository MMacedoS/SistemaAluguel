<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';

class PagamentoModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    
    protected $conexao;

    protected $model = "pagamento";

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();
    }

    public function inserirPagamento($dados, $reserva_id)
    {
        $tipo = $dados['tipo'];
        $valor =  $dados['valor'];
        $descricao =  $dados['descricao'];

        if(empty($descricao))
        {
            $descricao = "Pagamento referente a reserva $reserva_id";
        }

        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    tipoPagamento =:tipo, 
                    valorPagamento = :valor, 
                    dataPagamento = :dataPagamento,
                    descricao = :descricao,
                    reserva_id = :reserva_id,
                    funcionario = :funcionario
                    "
                );

            $cmd->bindValue(':tipo',$tipo);
            $cmd->bindValue(':descricao',$descricao);
            $cmd->bindValue(':dataPagamento', Date('Y-m-d'));
            $cmd->bindValue(':valor',$valor);
            $cmd->bindValue(':reserva_id',$reserva_id);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function getDadosPagamentos($id){
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
            return self::messageWithData(201, 'pagamentos encontrados', $dados);
        }

        return self::messageWithData(201, 'nenhum dado encontrado', []);
    }

    public function getRemovePagamento($id){
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