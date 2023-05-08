<?php

trait GeneralTrait{

    public function prepareStatusReserva($status){
        switch ($status) {
            case '1':
                    return 'Reservada';
                break;
            case '2':
                return 'Confirmada';
            break;

            case '3':
                return 'Hospedada';
            break;

            case '4':
                return 'Fechada';
            break;

            case '5':
                return 'Cancelada';
            break;
            
            default:
                # code...
                break;
        }
    }

    public function prepareTipoReserva($tipo) {
        switch ($tipo) {
            case '1':
                    return 'Diaria';
                break;
            case '2':
                return 'Pacote';
            break;

            case '3':
                return 'Promoção';
            break;
            
            default:
                # code...
                break;
        }
    }

   public function prepareTipo($value)
    {
        $res = "";
        switch ($value) {
            case '1':
                $res = "Dinheiro";
            break;
            case '2':
                $res = "Cartão de Crédito";
            break;
            case '3':
               $res =  "Cartão de Débito";
            break;
            case '4':
               $res =  "Déposito/PIX";
            break;
        }

        return $res;
    }

    public function prepareTipoSaidas($value)
    {
        $res = "";
        switch ($value) {
            case '1':
                $res = "Adiantamento";
            break;
            case '2':
                $res = "Operacionais";
            break;
            case '3':
               $res =  "Pessoal";
            break;
            case '4':
               $res =  "Outros";
            break;
        }

        return $res;
    }

    public function prepareTipoEntradas($value)
    {
        if(is_null($value))
        {
            return "Saida";

        }
        return "Entrada";
    }

    public function calculateEntrada($movimentos)
    {
        if(is_null($movimentos))
        {
            return 0;
        }

        $total = 0;

        foreach ($movimentos as $key => $value) {
            if(!is_null($value['entrada_id']))
            {
                $total+=$value['valor'];
            }
        }   

        return number_format($total,2,',', '');
    }

    public function calculateSaida($movimentos)
    {
        if(is_null($movimentos))
        {
            return 0;
        }

        $total = 0;

        foreach ($movimentos as $key => $value) {
            if(!is_null($value['saida_id']))
            {
                $total+=$value['valor'];
            }
        }   

        return number_format($total,2,',', '');
    }

    public function prepareTipoVenda($value)
    {
        if(is_null($value))
        {
            return "Vendas";

        }
        return "Hospedagem";
    }
}