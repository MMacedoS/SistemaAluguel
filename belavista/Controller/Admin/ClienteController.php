<?php

class ClienteController extends \Controller{

    protected $cliente_model;

    public function __construct() {
        $this->cliente_model = new ClienteModel(); 
    }

    public function buscaCliente($request =  null)
    {
        return $this->cliente_model->findCliente($request);
    }

    public function buscaClientePorId($id)
    {
        echo json_encode($this->cliente_model->findById($id));
    }

    public function salvarCliente() {
        $hospede = $this->cliente_model->prepareInsertCliente($_POST);
        echo json_encode($hospede);
    }

    public function atualizarCliente($id)
    {
        $hospede = $this->cliente_model->prepareUpdateCliente($_POST, $id);
        echo json_encode($hospede);
    }

    public function changeStatusCliente($id)
    {
        $hospede = $this->cliente_model->prepareChangedCliente($id);
        echo json_encode($hospede);
    }    
}