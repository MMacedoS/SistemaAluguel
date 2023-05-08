<?php

class AdministrativoController extends \Controller{
    protected $financeiro_controller;
    
    public function __construct() {
        $this->validPainel();     
        $this->financeiro_controller = new FinanceiroController();  
        $this->material_model = new MaterialModel();      
        $this->cliente_model = new ClienteModel();  
    }

    private function validPainel() {        
        if ($_SESSION['painel'] != 'Administrador' && $_SESSION['painel'] != 'Recepcao') {   
            session_start();
            session_destroy();            
            return header('Location: '.$this->url.'/Login');            
        }       
    }

    public function index() {        
        $this->viewAdmin('consultas');
    }

    // material
    public function material($request = null) {
        $this->viewAdmin('material',$request,"");
    }

    public function buscaMaterial($request = null)
    {
        return $this->material_model->findMaterial($request);
    }
    //

    // clientes
    public function clientes()
    {
        $this->viewAdmin('cliente',$request,"");
    }

    public function buscaCliente($request = null)
    {
        return $this->cliente_model->findCliente($request);
    }

     // reservas
     public function reservas($request = null)
     {
         $this->viewAdmin('reservas',$request,"");
     }
 
     public function buscaReservas($request = null)
     {
        $reserva_controller = new ReservaController();  
        return $reserva_controller->buscaReservas($request);     }

    public function consultas($request = null) {
            $this->viewAdmin('consultas',$request,"");
    }

    public function hospedadas($request = null) {
        $this->viewAdmin('hospedadas',$request,"");
    }

    public function buscaHospedadas($request =  null)
    {        
        $reservas_controller = new ReservaController();
        return $reservas_controller->buscaHospedadas($request);
    }

    public function checkin($request = null) {
        $this->viewAdmin('checkin',$request,"");
    }

    public function buscaCheckin($request =  null)
    {        
        $reservas_controller = new ReservaController();
        return $reservas_controller->buscaCheckin($request);
    }

    public function movimentacoes($request = null)
    {
        $this->viewAdmin('movimentacoes',$request,"");
    }

    public function buscaMovimentos($request = null)
    {
        return $this->financeiro_controller->buscaMovimentos($request);
    }

    public function entrada($request = null)
    {
        $this->viewAdmin('entrada',$request,"");
    }

    public function buscaEntrada($request = null)
    {
        return $this->financeiro_controller->buscaEntrada($request);
    }

    public function saida($request = null)
    {
        $this->viewAdmin('saida',$request,"");
    }

    public function buscaSaida($request = null)
    {
        return $this->financeiro_controller->buscaSaida($request);
    }
        //menu
    

    public function listMaterial()
    {
        return [];
    }

    public function buscaCheckout()
    {
        return [];
    }


}