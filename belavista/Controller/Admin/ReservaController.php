<?php

class ReservaController extends \Controller{
    use DateTrait;

    protected $reserva_model;

    public function __construct() {
        $this->reserva_model = new ReservaModel();      
    }

    public function buscaReservas($request =  null)
    {
        if(is_null($request)){
            return $this->reserva_model->findAllReservas('', 0);    
        }
        $dados = explode('_@_', $request);   

        if(count($dados) == 1){
            $chave = str_replace('page=', '', $dados[0]);
            return $this->reserva_model->findAllReservas('', $chave);    
        }
    
        return $this->reserva_model->findReservas(
            $dados[0],
            $dados[1], 
            $dados[2], 
            $dados[3]
        );
    }

    public function buscaReservaPorId($id)
    {
        echo json_encode($this->reserva_model->findById($id));
    }

    public function salvarReservas() {
        $reserva = $this->reserva_model->prepareInsertReserva($_POST);
        echo json_encode($reserva);
    }

    public function atualizarReserva($id)
    {
        $reserva = $this->reserva_model->prepareUpdateReserva($_POST, $id);
        echo json_encode($reserva);
    }

    public function changeStatusReservas($id)
    {
        $reserva = $this->reserva_model->prepareChangedReserva($id);
        echo json_encode($reserva);
    }

    public function reservaBuscaPorData(){
        $reserva = $this->reserva_model->apartamentoDisponiveisPorData($_POST['dataEntrada'], $_POST['dataSaida']);
        echo json_encode($reserva);
    }

    public function buscaHospedadas($request = null)
    {
        if(is_null($request)){
            return $this->reserva_model->buscaHospedadas('', 3,'','');    
        }
        $dados = explode('_@_', $request);          
    
        return $this->reserva_model->buscaHospedadas(
            $dados[0],
            3, 
            $dados[2], 
            $dados[3]
        );
    }

    public function executaCheckout($id) 
    {
        $reserva = $this->reserva_model->executaCheckout($id);
        echo json_encode($reserva);
    }

    public function buscaCheckin($request = null)
    {
        $reserva = $this->reserva_model->buscaCheckin($request);
        return $reserva;
    }

    public function changeCheckinReservas($code)
    {
        $reserva = $this->reserva_model->prepareCheckinReserva($code);
        echo json_encode($reserva);
    }

    public function buscaCheckout($request = null)
    {
        $reserva = $this->reserva_model->buscaCheckout($request);
        return $reserva;
    }

    public function getDadosReservas($code)
    {
        $reserva = $this->reserva_model->getDadosReservas($code);
        echo json_encode($reserva);
    }

    public function buscaConfirmada($request = null)
    {
        $reserva = $this->reserva_model->buscaConfirmada($request);
        return $reserva;
    }

    public function gerarDiarias()
    {
       return $this->reserva_model->gerarDiarias();
    }
}