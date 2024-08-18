<?php

abstract class Diretorio {
    /**
     * Alterar diretório
     */
    const diretorio = "\\Users\\Acer\\DESKTOP\\github\\meu-financeiro";

    public static function getDiretorio()
    {
        return __DIR__;
    }
}