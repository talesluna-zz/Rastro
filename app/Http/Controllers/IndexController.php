<?php

namespace App\Http\Controllers;

use App\Services\ParserCorreiosService;
use App\Services\ResponseService;
use App\Services\TransformObjectsService;

class IndexController extends Controller
{

    private $response;  // Esse serviço envia a response HTTP
    private $transform; // Esse serviço transforma os dados de array para o tipo escolhido
    private $parser;    // Esse serviço transforma o HTML dos correios em array com os objetos

    public function __construct()
    {
        $this->parser   = new ParserCorreiosService();
        $this->response = new ResponseService();
        $this->transform= new TransformObjectsService();
    }

    /**
     * Somente dados da API
     * @return string
     */
    public function index()
    {
        return $this->response->send('txt', 'Olá Mundo', 400);
    }

    /**
     *
     * Retorna dados de rastreio no tipo informado em parâmetro
     * @param string $outputType
     * @param string $objects
     * @return string
     */
    public function discoverObjects(string $outputType, string $objects)
    {
        if ($objects) {

            $objects = $this->parser->retrieverObjects($objects);
            $objects = $this->transform->transform($objects, $outputType);
            return $this->response->send($outputType, $objects, 200);
        }
    }
}
