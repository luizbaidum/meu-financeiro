<?php

abstract class Diretorio {
    /**
     * Alterar diretório
     */
    const diretorio = "\\Users\\luizb\\Desktop\\github\\meu-financeiro";

    public static function getDiretorio()
    {
        return __DIR__;
    }
}