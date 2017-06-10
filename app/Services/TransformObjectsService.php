<?php

namespace App\Services;


use Illuminate\Support\Collection;
use LSS\Array2XML;
use mnshankar\CSV\CSV;

class TransformObjectsService
{

    private $data;

    /**
     * Receive data and transform in type defined calling private functions
     * @param Collection $data
     * @param string $type
     * @return mixed
     * @throws \Exception
     */
    public function transform(Collection $data, string $type)
    {
        if(method_exists($this, $type)) {
            $this->data = $data->toArray();
            return $this->{$type}();
        } else {
            throw new \Exception('Tipo de retorno não implementado ou algum param faltando', 400);
        }
    }

    /**
     * Return a JSON string
     * @return string
     */
    private function json()
    {
        return json_encode($this->data);
    }

    /**
     * Return a XML string
     * @return string
     */
    private function xml()
    {
        $xml = Array2XML::createXML('rastreio', $this->data);
        return $xml->saveXML();
    }

    /**
     * Return a CSV string
     * @return string
     */
    private function csv()
    {
        $csvObj = new CSV();
        //header('Content-disposition: filename="rastreio.csv"');
        return $csvObj->fromArray($this->data)->toString();
    }

}