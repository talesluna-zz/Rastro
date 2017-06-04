<?php

namespace App\Services;

class RequestCorreiosService
{
    /**
     * URL oficial dos correios onde iremos realizar a consulta dos objetos
     * @var string
     */
    private $URL = 'http://www2.correios.com.br/sistemas/rastreamento/multResultado.cfm';

    /**
     * Conforme formulario na pagina dos correios
     * @var array
     */
    private $POST_FORM = [
        'objetos' => ''
    ];

    /**
     * Realiza a request e retorna o HTML
     * @param string $objects
     * @return string
     */
    protected function getResponse(string $objects) : string
    {
        try{
            // Inserindo os objetos no form
            $this->POST_FORM['objetos'] = $objects;
            // Request...
            $request = curl_init($this->URL);
            curl_setopt($request, CURLOPT_POST, true);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($request, CURLOPT_POSTFIELDS, $this->POST_FORM);
            $response = curl_exec($request);
            curl_close($request);
            return $response;
        } catch (\Exception $ex) {
          dd($ex->getMessage());
        }

    }
}