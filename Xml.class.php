<?php

class Xml extends ParseWebSro{

    function __construct($code){

        // Seta o código de rastreio para o parser
        parent::__construct($code);

    }

    // Imprime o texto de saída (XML ou JSON)
    function getOutput(){

        // Nomes de refêrencia para os dados de saída.
        $keys   = array("data", "local", "situacao");
        $count  = 0;

        // Array para armazenar os encaminhamentos...
        $encaminhados = array();
        $countE = 0;

        // Array com os dados retornardos pelo WebSRO dos correios...
        $dados = parent::parse();


        // Procura por encaminhamentos de mercadoria...
        foreach($dados as $line){

            // Gera um array com base na separação CSV da linha (Consultar Parser)
            $line = str_getcsv($line);

            // Array com 1 linha são detalhes dos encaminhamentos...
            if(count($line) == 1){
                $encaminhados[] = $line;
            }
        }


        // Inicio do XML
        echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";

        // Root Tag tendo o código de rastreio como atributo
        echo "<rastreamento cod='".$this->websro_code."'>\n";


        // Procura pelos valores em meio ao array de dados do WebSRO dos correios.
        foreach($dados as $line){

            // Gera um array com base na separação CSV da linha (Consultar Parser)
            $line = str_getcsv($line);

            // Verifica contador de array
            if($count == count($line)){
                $count = 0;
            }

            // Array com todos os dados possuem 3 linhas
            if(count($line) == 3){

                // Elemento com dados do rastreio...
                echo "\t<rastreio>\n";

                // Procura pelos dados do rastreio
                foreach($line as $values){

                    $values = rtrim($values);

                    // Se a situação for um encaminhamento, subistituir pelo detalhe deste encaminhamento..
                    if($values == " Encaminhado"){
                        $values = $encaminhados[$countE++][0];
                    }

                    // Elemento com o dado de rastreio, o nome do elemento segue o array de referências
                    echo "\t\t <".$keys[$count].">".rtrim($values)."</".$keys[$count].">\n";
                    $count++;
                }

                // Fecha elemento com dados de rastreio
                echo "\t</rastreio>\n";
            }

        }

        // Fecha a Root Tag
        echo "</rastreamento>\n";

    }

    // END
}