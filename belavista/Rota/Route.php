<?php

class Route {
    protected String $controller = 'SiteController';
    protected String $method = 'index';
    protected array $parameter = [];

    public function __construct(){
        $this->prepareUrl();
    }

    public function prepareUrl() {
        if(!isset($_GET['pag']))
        {
            $this->controller="SiteController";
            $this->method="index";            
            $this->parameter = array();
        }
        else {
            $url=$_GET['pag'];  
            
            if($url == "gerardiaria")
            {
                $this->controller="AppController";
                $this->method="gerarDiarias";            
                $this->parameter = array();
                
                $this->run();                
            }

            $url= explode('/',$url);

            $this->controller = $url[0]."Controller";
            array_shift($url);
            
            if (isset($url[0]) && !empty($url)) {
                $this->method = $url[0];
                array_shift($url);

                if(count($url)>0)
                {
                    $this->parameter= (array) $url;
                }
            }
        }
        
        $this->run();
    }

    private function run()
    {
        $this->prepareRota();
        $control=new $this->controller;
        call_user_func_array(array($control,$this->method),$this->parameter);
    }

    private function prepareRota()
    {
        if (file_exists('./Controller/'.$this->controller.'.php') && method_exists($this->controller,$this->method)) {
            return true;
        }

        if (file_exists('./Controller/Admin/'.$this->controller.'.php') && method_exists($this->controller,$this->method)) {
            return true;
        }

        if (file_exists('./Controller/API/'.$this->controller.'.php') && method_exists($this->controller,$this->method)) {
            return true;
        }

        $this->controller = 'SiteController';
        $this->method = 'index';
        return false;
    }
}