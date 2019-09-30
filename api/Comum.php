<?php

    abstract class Comum {

        //Função para limpar um INPUT
        public static function limparHTML( $input ) {
            return htmlspecialchars( trim( $input ) );
        }

    }

?>