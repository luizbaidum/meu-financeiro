<?php

class NumbersHelper {

    public static function onlyNumbers($str)
    {
        $str = preg_replace('/\D/', '', $str);
        return $str;
    }

    public static function formatBRtoUS($num)
	{
        if ($num == null || $num == '')
            return '';

        $num = str_replace(',', '', $num);
        $formated = number_format($num, 2, ',', '.');

        return $formated;
    }
    
    public static function formatUStoBR($num)
	{
        if ($num == null || $num == '')
            return '';
            
        $num = str_replace(',', '', $num);
        $formated = number_format($num, 2, ',', '.');

        return $formated;
    } 
}

class DateHelper {
    public static function convertUStoBR($date)
    {
        if ($date == null || $date == '')
            return ''; 

        date_default_timezone_set('America/Sao_Paulo');
        $ob_data_atual = new DateTime($date);
        $data_formatada = $ob_data_atual->format('d/m/Y');

        return $data_formatada;
    }
}