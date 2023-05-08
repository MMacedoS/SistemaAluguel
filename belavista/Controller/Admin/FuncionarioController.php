<?php

class FuncionarioController extends \Controller{

    protected $funcionario_model;

    public function __construct() {
        $this->funcionario_model = new FuncionarioModel();      
    }

    public function buscaFuncionarios($request =  null)
    {
        return $this->funcionario_model->findFuncionarios($request);
    }

    public function buscaFuncionarioPorId($id)
    {
        echo json_encode($this->funcionario_model->findById($id));
    }

    public function salvarFuncionarios() {
        $funcionario = $this->funcionario_model->prepareInsertFuncionario($_POST);
        echo json_encode($funcionario);
    }

    public function atualizarFuncionarios($id)
    {
        $funcionario = $this->funcionario_model->prepareUpdateFuncionario($_POST, $id);
        echo json_encode($funcionario);
    }

    public function changeStatusFuncionarios($id)
    {
        $funcionario = $this->funcionario_model->prepareChangedFuncionario($id);
        echo json_encode($funcionario);
    }
}