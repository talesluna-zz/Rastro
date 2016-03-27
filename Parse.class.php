<?php

class ParseWebSro{

    // URL do WebSRO
    private $websro_url = "http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_COD_UNI=";

    // Código de rastreamento.
    protected $websro_code;


    // Deve-se chamar o contrutor ao extender a classe informado o código de rastreio
    function __construct($code){
        $this->websro_code = $code;
    }

    function parse(){


            // Requisita ao WebSRO o content HTML para parsear
            $source = utf8_encode(@file_get_contents($this->websro_url.$this->websro_code));


            //Criar um CSV para organizar os dados...
            $source = preg_replace("/<td>/", " , ", $source);


            // Limpar todas as tags HTML menos a <tr>
            $source = strip_tags($source, "<tr>");


            // Verificar se houve retorno...
            if(!empty($source)) {

                // Procura por linhas com a tag <tr> ... se existir retorna um array
                if (preg_match_all("@<tr>(.*)</tr>@", $source, $math_array, PREG_PATTERN_ORDER)) {

                    // Retorna um array com os matches encontrados...
                    return array_reverse($math_array[1]);

                }else{

                    // Para a execução e mostra que não houve dados sobre o código informado...
                    die("Não foi encontrado infromações para o código de rastreio informado.");

                }
            }else{

                // Para a execução e mostra que houve um erro ao receber o HTML do WebRso
                die("Erro ao conectar com o WebSro dos correios.");

                /* PS:
                    Caso não queia exibir está mensagem ainda mantenha o die() para que a execução não continuem com um arry vazio...
                    Lembre-se: este é um "recurso técnico aloternativo" aconselhavel estudar o WebService dos correios...
                */
            }


    }


}