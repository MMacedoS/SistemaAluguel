<?php

class ProdutoController extends \Controller{
    use DateTrait;

    protected $produto_model;

    public function __construct() {
        $this->produto_model = new ProdutoModel();      
    }

    public function getDadosProdutos()
    {
        echo  json_encode($this->produto_model->getProdutos());
    }

    public function buscaProduto($request =  null)
    {
        return $this->produto_model->getProdutos()['data'];
    }

    public function buscaProdutoPorId($id)
    {
        echo json_encode($this->produto_model->findById($id));
    }

    public function salvarProdutos() {
        $produto = $this->produto_model->prepareInsertProduto($_POST);
        echo json_encode($produto);
    }

    public function atualizarProduto($id)
    {
        $produto = $this->produto_model->prepareUpdateProduto($_POST, $id);
        echo json_encode($produto);
    }

    public function changeStatusProduto($id)
    {
        $produto = $this->produto_model->prepareChangedProduto($id);
        echo json_encode($produto);
    }

    public function buscaEntradaProduto($request =  null)
    {
        return $this->produto_model->buscaEntradaProduto()['data'];
    }

    public function buscaProdutos()
    {       
        return $this->produto_model->buscaProdutos();
    }

    public function buscaEstoques($request = null)
    {       
        return $this->produto_model->buscaEstoques($request)['data'];
    }

    public function salvarEntrada()
    {
        echo json_encode($this->produto_model->salvarEntrada($_POST));
    }

    public function deleteEntrada($id)
    {
        echo json_encode($this->produto_model->deleteEntrada($id));
    }
}