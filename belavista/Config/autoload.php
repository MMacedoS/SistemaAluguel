<?php
if (!isset($_SESSION)) { 
    session_start();
}
spl_autoload_register(function($instancia) {
    if(file_exists("./Controller/" . $instancia . ".php")) {
        require './Controller/'.$instancia.'.php';
    }

    elseif(file_exists("./Controller/Admin/" . $instancia . ".php")) {
        require './Controller/Admin/'.$instancia.'.php';
    }

    elseif(file_exists("./Model/" . $instancia . ".php")) {
        require './Model/'.$instancia.'.php';
    }

    elseif(file_exists("./Rota/".$instancia.".php")) {
        require './Rota/'.$instancia.'.php';
    }

    elseif(file_exists("./Controller/API/".$instancia.".php")) {
        require './Controller/API/'.$instancia.'.php';
    }
});