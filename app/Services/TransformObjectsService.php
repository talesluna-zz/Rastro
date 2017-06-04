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
     */
    public function transform(Collection $data, string $type)
    {
        $this->data = $data->toArray();
        return $this->{$type}();
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