<?php

class ConsumoController extends \Controller{
    use DateTrait;

    protected $consumo_model;

    public function __construct() {
        $this->consumo_model = new ConsumoModel();      
    }


    public function addConsumo($id)
    {
        $dados = $this->consumo_model->inserirConsumo($_POST, $id);
        echo json_encode($dados);
    }

    public function getDadosConsumos($code)
    {
        $reserva = $this->consumo_model->getDadosConsumos($code);
        echo json_encode($reserva);
    }

    public function getRemoveConsumo($code)
    {
        $reserva = $this->consumo_model->getRemoveConsumo($code);
        echo json_encode($reserva);
    }

}