<?php

abstract class Diretorio {
    /**
     * Alterar diretório
     */
    const diretorio = "\\Users\\ACER\\Desktop\\GITHUB\\meu-financeiro";

    public static function getDiretorio()
    {
        return __DIR__;
    }
}