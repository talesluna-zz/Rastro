<?php

class Json extends ParseWebSro{

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
        $countD = 0;    // Contador do array de dados...

        // Procura por encaminhamentos de mercadoria...
        foreach($dados as $line){

            // Gera um array com base na separação CSV da linha (Consultar Parser)
            $line = str_getcsv($line);

            // Array com 1 linha são detalhes dos encaminhamentos...
            if(count($line) == 1){
                $encaminhados[] = $line;
                $countD++;
            }
        }

        // Inicio do JSON
        echo "{\n";

        echo "\t".json_encode($this->websro_code).":[\n";

        // Procura pelos valores em meio ao array de dados do WebSRO dos correios.
        foreach($dados as $line){

            // Gera um array com base na separação CSV da linha (Consultar Parser)
            $line = str_getcsv($line);




            // Array com todos os dados possuem 3 linhas
            if(count($line) == 3){

                // Elemento com dados do rastreio...
                echo "\t\t{\n";

                // Procura pelos dados do rastreio
                foreach($line as $values){

                    $values = rtrim($values);

                    // Se a situação for um encaminhamento, subistituir pelo detalhe deste encaminhamento..
                    if($values == " Encaminhado"){
                        $values = $encaminhados[$countE++][0];
                    }

                    // Rotulo com o dado de rastreio, o rotulo do dado segue o array de referências
                    echo "\t\t\t".json_encode($keys[$count]).":".json_encode($values);
                    $count++;

                    // Verifica contador de array
                    if($count == count($line)){
                        $count = 0;
                        // Se for o ultimo dado não recebe virgula
                        echo "\n";
                    }else{
                        // Se não for o ultimo dado recebe virgula
                        echo ",\n";
                    }
                }

                $countD++;
                if($countD != count($dados)){
                    //$countD = 0;
                    // Fecha elemento com dados de rastreio com virgula
                    echo "\t\t},\n";
                }else{
                    // Fecha elemento com dados de rastreio sem virgula
                    echo "\t\t}\n";
                }



            }

        }

        // Fecha a JSON
        echo "\t]\n";
        echo "}\n";


    }

    // END
}