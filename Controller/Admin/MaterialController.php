<?php

class MaterialController extends \Controller{

    public function __construct() {
        $this->material_model = new MaterialModel();      
    }

    public function getDadosMaterial()
    {
        echo  json_encode($this->material_model->getMaterial());
    }

    public function buscaMaterial($request =  null)
    {
        return $this->material_model->findMaterial($request);
    }

    public function buscaMaterialPorId($id)
    {
        echo json_encode($this->material_model->findById($id));
    }

    public function salvarMaterial() {
        $apartamento = $this->material_model->prepareInsertMaterial($_POST);
        echo json_encode($apartamento);
    }

    public function atualizarMaterial($id)
    {
        $apartamento = $this->material_model->prepareUpdateMaterial($_POST, $id);
        echo json_encode($apartamento);
    }

    public function changeStatusMaterial($id)
    {
        $apartamento = $this->material_model->prepareChangedMaterial($id);
        echo json_encode($apartamento);
    }
}