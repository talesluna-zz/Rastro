<?php

namespace App\Services;

class ResponseService
{

    private $MIMES = [
        'json'  => 'application/json',
        'xml'   => 'text/xml',
        'csv'   => 'text/plain',
        'txt'   => 'text/plain'
    ];

    public function send(string $outputType, $data, int $status)
    {
        return response(
            $data,
            $status)
            ->header(
                'Content-Type', $this->MIMES[$outputType]
            );
    }

}