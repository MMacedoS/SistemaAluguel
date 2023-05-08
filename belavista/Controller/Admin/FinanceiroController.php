<?php

class FinanceiroController extends \Controller{
    use DateTrait;

    protected $financeiro_model;

    public function __construct() {
        $this->financeiro_model = new FinanceiroModel();      
    }
    
    public function buscaMovimentos($request = null)
    {       
        return $this->financeiro_model->buscaMovimentos($request);
    }

    public function buscaEntrada($request = null)
    {       
        return $this->financeiro_model->buscaEntrada($request);
    }

    public function buscaSaida($request = null)
    {       
        return $this->financeiro_model->buscaSaida($request);
    }

    public function insertSaida()
    {
        echo json_encode($this->financeiro_model->insertSaida($_POST));
    }

    public function removerSaida($id)
    {
        echo json_encode($this->financeiro_model->deleteSaida($id));
    }

    public function salvarEntradas()
    {
        echo json_encode($this->financeiro_model->insertEntrada($_POST));
    }
}