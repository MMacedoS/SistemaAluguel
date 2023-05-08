<?php

trait DateTrait {

    public function addDdayInDate($date = null, $intervalo)
    {
        if(is_null($date)){
            $date = new DateTime();
            date_add(
                $date,  
                date_interval_create_from_date_string("$intervalo days")
            );
            return $date->format('Y-m-d');
        }  

        $date = new DateTime($date);
        date_add(
            $date,  
            date_interval_create_from_date_string("$intervalo days")
        );
        return $date->format('Y-m-d');
        
    }

    public function addDayInDate($date = null, $intervalo)
    {
        $date = new DateTime($date);
        date_add(
            $date,  
            date_interval_create_from_date_string("$intervalo days")
        );
        return $date->format('Y-m-d H:i:s');
        
    }

    public function prepareDateBr($date)
    {
        if(is_null($date)){
            $date = Date('Y-m-d');
        }  

        return implode('/',
        array_reverse(
            explode('-', $date)
        ));
    }

}