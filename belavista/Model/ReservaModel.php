<?php

require_once 'Trait/StandartTrait.php';
require_once 'Trait/FindTrait.php';
require_once 'Trait/DateModelTrait.php';

class ReservaModel extends ConexaoModel {

    use StandartTrait;
    use FindTrait;
    use DateModelTrait;
    
    protected $conexao;

    protected $model = 'reserva';

    protected $material_model;

    protected $consumo_model;

    public function __construct() 
    {
        $this->conexao = ConexaoModel::conexao();

        $this->material_model = new MaterialModel();
        $this->consumo_model = new ConsumoModel();
    }

    public function prepareInsertReserva($dados)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){
            
          
            return $this->insertReserva($dados); 

          
        }

        return $validation;
    }    

    private function insertReserva($dados)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "INSERT INTO 
                    $this->model 
                SET 
                    data_reserva = :data_reserva, 
                    data_devolucao = :data_devolucao, 
                    data_retirada = :data_retirada,
                    obs = :observacao,
                    responsavel = :responsavel,
                    cliente_id = :cliente_id,
                    telefone = :telefone,
                    status = :status,
                    multa = :multa,
                    endereco = :endereco,
                    funcionario =  :funcionario
                    "
                );

            $cmd->bindValue(':data_reserva', Date('Y-m-d'));
            $cmd->bindValue(':data_devolucao', self::prepareDateUS($dados['saida']));
            $cmd->bindValue(':data_retirada', self::prepareDateUS($dados['entrada']));
            $cmd->bindValue(':responsavel',$dados['responsavel']);
            $cmd->bindValue(':multa',$dados['multa']);
            $cmd->bindValue(':status',$dados['status']);
            $cmd->bindValue(':telefone',$dados['telefone']);
            $cmd->bindValue(':observacao',$dados['observacao']);
            $cmd->bindValue(':cliente_id',$dados['cliente']);
            $cmd->bindValue(':funcionario',$_SESSION['code']);
            $cmd->bindValue(':endereco',$dados['endereco']);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados inseridos!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareUpdatereserva($dados, $id)
    {
        $validation = self::requiredParametros($dados);

        if(is_null($validation)){            
            return $this->updateReserva($dados, $id); 
        }

        return $validation;
    }

    private function updateReserva($dados, int $id)
    {
        $this->conexao->beginTransaction();
        try {    
            
            $reserva = self::findById($id);
            
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    data_reserva = :data_reserva, 
                    data_devolucao = :data_devolucao, 
                    data_retirada = :data_retirada,
                    obs = :observacao,
                    responsavel = :responsavel,
                    cliente_id = :cliente_id,
                    funcionario = :funcionario,
                    telefone = :telefone,
                    status = :status,
                    multa = :multa,
                    endereco = :endereco,
                    funcionario =  :funcionario
                WHERE 
                    id = :id"
                );

                $cmd->bindValue(':data_reserva', Date('Y-m-d'));
                $cmd->bindValue(':data_devolucao', self::prepareDateUS($dados['saida']));
                $cmd->bindValue(':data_retirada', self::prepareDateUS($dados['entrada']));
                $cmd->bindValue(':responsavel',$dados['responsavel']);
                $cmd->bindValue(':multa',$dados['multa']);
                $cmd->bindValue(':status',$dados['status']);
                $cmd->bindValue(':telefone',$dados['telefone']);
                $cmd->bindValue(':observacao',$dados['observacao']);
                $cmd->bindValue(':cliente_id',$dados['cliente']);
                $cmd->bindValue(':funcionario',$_SESSION['code']);
                $cmd->bindValue(':endereco',$dados['endereco']);
                $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            return self::message(201, "dados Atualizados!!");

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function findReservas($nome, $status, $entrada, $saida)
    {

        $SQL = "SELECT 
                    r.*, 
                    h.nome
                FROM 
                    $this->model r 
                INNER JOIN
                    cliente h 
                ON 
                    r.cliente_id = h.id
                WHERE
                    r.status LIKE '%$status%'  
                ";
        
        if(!empty($entrada)){
            $SQL.= "
            AND
            (
                r.data_retirada 
                    BETWEEN 
                        '$entrada' 
                    AND 
                        '$saida'
                                       
            )";
        }

        if(!empty($nome)){
            $SQL.= "
            AND
            (
                h.nome LIKE '%$nome%' 
                                       
            )";
        }

        $cmd  = $this->conexao->query(
            $SQL
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
        
    }

    public function findAllReservas($nome, int $off = 0)
    {
        $off = $off;
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                cliente h 
            ON 
                r.cliente_id = h.id
            WHERE
                h.nome LIKE '%$nome%'
             AND
                r.status <=2   
             AND
                r.data_retirada >= DATE_SUB(curdate(), INTERVAL 3 DAY) 
            ORDER BY
                r.id DESC
            LIMIT 12 offset $off 
            
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
        
    }

    public function prepareChangedReserva($id)
    {
        $reserva = self::findById($id);

        if(is_null($reserva)) {
            return self::messageWithData(422, 'reserva não encontrado', []);
        }

        $reserva['data'][0]['status'] == '1' ? $status = 5 : $status = 1;
        
        return $this->updateStatusReserva(
                $status,
                $id,
                $reserva['data'][0]['apartamento_id']
            );
    }

    private function updateStatusReserva($status, $id)
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

    private function updateStatusCheckoutReserva($status, $id, $apartamento)
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
            
            $this->material_model->prepareChangedMaterialStatus($apartamento, 1);

            $this->conexao->commit();
            
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }

    public function prepareCheckinReserva($id)
    {
        $reserva = self::findById($id);

        if(is_null($reserva)) {
            return self::messageWithData(422, 'reserva não encontrado', []);
        }

        return $this->updateStatusReserva(
            3,
            $id
        );
    }

    private function getReservasPorData($dataStart, $dataEnd)
    {
        $cmd = $this->conexao->query(
            "SELECT 
                *
            FROM 
                reserva
            WHERE 
                (status <= 3)
            AND 
            ( 
                (
                    dataEntrada >= '$dataStart' 
                    AND 
                    dataEntrada < '$dataEnd'
                ) 
                OR 
                (
                    dataSaida > '$dataStart' 
                    AND 
                    dataSaida <= '$dataEnd'
                ) 
                OR 
                (
                    dataEntrada <= '$dataStart' 
                    AND 
                    dataSaida >= '$dataEnd' 
                )
            )"
        );

        if($cmd->rowCount() > 0) {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function buscaHospedadas($nome, $status, $entrada = null, $saida = null)
    {
        $SQL = "SELECT 
                    r.*, 
                    h.nome
                FROM 
                    $this->model r 
                INNER JOIN
                    cliente h 
                ON 
                    r.cliente_id = h.id
                WHERE
                    r.status LIKE '%$status%'  
                ";
        
        if(!empty($entrada)){
            $SQL.= "
            AND
            (
                r.data_retirada 
                    BETWEEN 
                        '$entrada' 
                    AND 
                        '$saida'
                                       
            )";
        }

        if(!empty($nome)){
            $SQL.= "
            AND
            (
                h.nome LIKE '%$nome%' 
                                       
            )";
        }

        $cmd  = $this->conexao->query(
            $SQL
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
        
    }

    public function buscaHospedadasAll($nome, $off = 0)
    {
        $off = $off;
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                cliente h 
            ON 
                r.cliente_id = h.id
            WHERE
                h.nome LIKE '%$nome%'
             AND
                r.status =   3
             AND
                r.data_retirada >= DATE_SUB(curdate(), INTERVAL 3 DAY) 
            ORDER BY
                r.id DESC
            LIMIT 12 offset $off 
            
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return false;
        
    }

    public function executaCheckout($id)
    {
        $reserva = self::findById($id);

        if(is_null($reserva)) {
            return self::messageWithData(422, 'reserva não encontrado', []);
        }

        $apartamento = $this->material_model->findById($reserva['data'][0]['apartamento_id'])['data'][0]['id'];

        return $this->updateStatusCheckoutReserva(
            4,
            $id,
            $apartamento
        );
    }

    public function buscaCheckin($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                cliente h 
            ON 
                r.cliente_id = h.id   
            WHERE
               (
                    r.status = 1 
                OR
                    r.status= 2
               )
               AND
                data_retirada <= curdate()
               AND
                data_retirada >= DATE_SUB(curdate(), INTERVAL 1 DAY)
            AND
                h.nome LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return array();
        
    }

    public function buscaCheckout($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome
            FROM 
                $this->model r 
            INNER JOIN
                cliente h 
            ON 
                r.cliente_id = h.id           
            WHERE
                r.status = 3
               AND
                r.data_devolucao <= curdate()
            AND
                h.nome LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
        
    }

    public function buscaConfirmada($nome)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome, 
                a.numero 
            FROM 
                $this->model r 
            INNER JOIN
                hospede h 
            ON 
                r.hospede_id = h.id
            LEFT JOIN 
                empresa_has_hospede eh
            ON 
                eh.hospede_id = h.id
            INNER JOIN 
                apartamento a 
            ON 
                r.apartamento_id = a.id
            WHERE
                r.status = 2
            AND
                h.nome LIKE '%$nome%'
            AND 
                dataEntrada LIKE '%$nome%'
            "
        );

        if($cmd->rowCount() > 0)
        {
            return $cmd->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
        
    }

    public function getDadosReservas($id){
        $cmd  = $this->conexao->query(
            "SELECT 
                r.*, 
                h.nome,
                COALESCE((SELECT sum(valor * quantidade) FROM consumo c where c.reserva_id = r.id), 0) as consumos,
                COALESCE((SELECT sum(p.valorPagamento) FROM pagamento p where p.reserva_id = r.id), 0) as pag
            FROM 
                `reserva` r 
            INNER JOIN 
                cliente h 
            on 
                r.cliente_id = h.id 
            WHERE 
                r.id = $id
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return self::messageWithData(201, 'reserva encontrada', $dados);
        }

        return self::messageWithData(422, 'nehum dado encontrado', []);
    }

    public function gerarDiarias()
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                *
            FROM 
                configuracao
            WHERE 
                parametro = 'gerar_diaria'
            "
        );

        if($cmd->rowCount() > 0)
        {
            $dados = $cmd->fetchAll(PDO::FETCH_ASSOC)[0]['valor'];
            // var_dump(strtotime(Date('Y-m-d 17:00:00')), strtotime(Date('Y-m-d H:i:s')));
            if (strtotime($dados) <= strtotime(Date('Y-m-d H:i:s'))) {
                $this->verificaGerarDiarias($dados);
            }

            return true;
        }
        
    }
    
    private function verificaGerarDiarias($param)
    {
        $cmd  = $this->conexao->query(
            "SELECT 
                id,
                valor,
                gerarDiaria
            FROM 
                reserva
            WHERE 
                status = 3
            AND
                tipo = 1
            AND
                gerarDiaria <= '$param'
            "
        );
       
        if($cmd->rowCount() > 0)
        {           
            $data = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $this->prepareGerarDiarias($data, $param);
        }

        return $this->updateConfiguracaoGerarDiaria(self::addDayInDate(Date('Y-m-d 16:00:00'), 1));
    }

    private function prepareGerarDiarias($dados, $param)
    {
        if(empty($dados))
        {
            return null;
        }

        foreach ($dados as $key => $value) {
            $dias = round((strtotime(Date('Y-m-d H:i:s')) - strtotime($value['gerarDiaria']))/86400);

            if($dias < 1){
                $dias = 1;
            }

            for ($i=1; $i <= $dias; $i++) { 
                $this->insertDiariaConsumo($value, self::addDayInDate($param, $i -1 ));
                $this->updateGerarDiaria($value['id'], self::addDayInDate($param, $i));

                $this->updateConfiguracaoGerarDiaria(self::addDayInDate($param, $i));
            }            
        }

        return "atalizações consumos feitas";
    }

    private function insertDiariaConsumo($value, $data)
    {        
        $this->consumo_model->insertDiaria($value, $data);
    }

    private function updateGerarDiaria($id, $data)
    {
        $this->conexao->beginTransaction();
        try {      
            $cmd = $this->conexao->prepare(
                "UPDATE 
                    $this->model 
                SET 
                    gerarDiaria = :gerarDiaria
                WHERE 
                    id = :id"
                );
            $cmd->bindValue(':gerarDiaria',$data);
            $cmd->bindValue(':id',$id);
            $dados = $cmd->execute();

            $this->conexao->commit();
            
            return self::messageWithData(200, "dados Atualizados!!", []);

        } catch (\Throwable $th) {
            $this->conexao->rollback();
            return self::message(422, $th->getMessage());
        }
    }


    private function updateConfiguracaoGerarDiaria($data)
    {
        $cmd  = $this->conexao->prepare(
            "UPDATE 
                configuracao
            SET 
              valor = :data 
            WHERE 
                parametro = :param
            "
        );

        $cmd->bindValue(':data',$data);
        $cmd->bindValue(':param',"gerar_diaria");

        $cmd->execute();

        return "atualizado configurações";
    }
}