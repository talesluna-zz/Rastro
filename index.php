<?php

    /*==============================================================================================*
     *
     *  Rastro Web API - Rastreamento de Objetos dos Correios - PHP
     *
     *  Atualizado em: 21/07/2016
     *
     *
     *  Author:
     *      Tales Luna
     *      http://taleslunadev.com/ | contato@taleslunadev.com
     *
     *
     *  É feita uma requisição HTTP ao WebSRO (http://websro.correios.com.br/) informando
     *  o código de rastreio e a resposta HTML é parseada para fromar uma sída em XML ou JSON.
     *
     *  ARQUIVOS:
     *      Parse.class.php : Classe que realiza parse do HTML e cria um array com os dados
     *      Xml.class.php   : Classe que gera saída em XML
     *      Json.class.php  : Classe que gera saída em JSON
     *      Index.php       : Recebe as intruções e realiza
     *      web.conf        : Configurações para URL amigavel em IIS
     *      .htaccess       : Configurações para URL amigavel em Apache
     *
     *
     *
     *  Response Codes:
     *      200 - Informações presentes e exibidas
     *      404 - Não foram encontradas informações de rastreio
     *      408 - Falha ao se conectar com os correios
     *      500 - Erro nas classes da aplicação
     *      403 - Tipo de retorno desejado não está implementado
     *
     *
     *
     *  EXEMPLOS:
     *      JSON:
     *          http://taleslunadev.com/rastro/json/DMXXXXXXXXXBR
     *      XML:
     *          http://taleslunadev.com/rastro/xml/DMXXXXXXXXXBR
     *
     *
     *  ATENÇÃO: Este é um "recurso técnico alternativo" para obter informações
     *           de objetos postados nos correios através do código de rastreamento
     *           do mesmo. É uma alternativa ao WebService dos correios.
     *
     *==============================================================================================*/

    // Habilitar exibição de erros
    ini_set("display_errors", 1);

    // Recebe as informação via URL no padrão: tipo/código_rastreio
    $request = filter_input(INPUT_GET, "req");

    // Separa as infromações da URL
    $values = array_filter(explode("/", $request));

    // Verifica se 2 valores foras informados
    if(count($values) == 2) {

        // Require da classe ParserWebSro para extender às outras...
        require_once "Parse.class.php";

        // Define values by URL...
        $class  = ucfirst(strtolower($values[0]));
        $code   = strtoupper($values[1]);

        // Require class output type...
        if(file_exists($class.".class.php")){

            require_once "$class.class.php";

            if(class_exists($class)){
                // Instancia a classe
                $class = new $class($code);


                // Imprime o texto de saída com os dados...
                $class->getOutput();

                // Altera o content-type da página o mime do tipo de saída escolhido (XML ou JSON)
                header("Content-Type: application/".strtolower(get_class($class)));
                header('Content-disposition: inline; filename="'.$code.'.'.strtolower(get_class($class)).'"');


            }else{
                // O tipo de retorno não confere com a classe do arquivo.
                http_response_code(500);
            }

        }else{
            // O tipo de retorno desejado não está implementado em classes.
            http_response_code(403);
        }

    }else{
        // Uso correto: tipo_retorno/codigo_rastreio
        http_response_code(403);
    }

// FIM

