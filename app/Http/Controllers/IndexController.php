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
        return $this->response->send('txt', '', 400);
    }

    /**
     *
     * Retorna dados de rastreio no tipo informado em parâmetro
     * @param string $outputType
     * @param string $objects
     * @return string
     */
    public function discoverObjects(string $outputType, $objects = null)
    {
        if ($objects) {
            try {
                $objects = $this->parser->retrieverObjects($objects);
                $objects = $this->transform->transform($objects, $outputType);
                return $this->response->send($outputType, $objects, 200);
            } catch (\Exception $ex) {
                // Se o tipo de retorno não existir, usar json
                $outputType = $ex->getCode() == 400 ? 'json': $outputType;
                $error = $this->transform->transform(
                    collect([
                        'error' => [
                            'message'   =>$ex->getMessage(),
                            'status'    => $ex->getCode()
                        ]
                    ]),
                    $outputType
                );
                return $this->response->send($outputType, $error, $ex->getCode());
            }
        }
        $error = $this->transform->transform(
            collect([
                'error' => [
                    'message'   => 'Informe os objetos (use ; para vários objetos)',
                    'status'    => '400'
                ]
            ]),
            $outputType
        );
        return $this->response->send($outputType, $error, 400);
    }
}
