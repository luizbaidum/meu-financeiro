<?php

abstract class Diretorio {
    /**
     * Alterar diretório
     */
    const diretorio = "\\Users\\Acer\\Desktop\\GITHUB\\meu-financeiro";

    public static function getDiretorio()
    {
        return __DIR__;
    }
}