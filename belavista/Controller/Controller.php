<?php

require_once "./Config/autoload.php";
require_once 'Trait/DateTrait.php';
require_once 'Trait/GeneralTrait.php';

class Controller {

    use GeneralTrait;
    use DateTrait;

    protected $site_model;
    protected $disciplina_id;
    protected $background;
    protected $material_model;
    protected $cliente_model;

    public function __construct(){
        $this->site_model = new SiteModel();
    }

    public function viewSite($view) {
        require "View/Site/{$view}.php";
    }

    public function viewAdmin($view, $request = null, $page = null) {
        $sub_view = $page;
        $painel = $view;
        
        if(!is_null($request)) {
            $dados = explode('_@_', $request);  
            $texto = $dados[0];
            if(count($dados) == 1){
                $chave = str_replace('page=', '', $dados[0]);
                $texto = '';
            }      
            
            $status = $dados[1];
            $entrada = $dados[2];
            $saida = $dados[3];
        }

        require "View/Administrativo/views.php";
    }
}
