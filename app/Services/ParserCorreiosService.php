<?php

namespace App\Services;


use Illuminate\Support\Collection;

class ParserCorreiosService extends RequestCorreiosService
{
   public function retrieverObjects(string $objects) : Collection
   {
       /**
        * Ao final do processo essa collection será retornada
        */
       $objects_list = collect([]);

       // Recebe HTML da request
       $data = $this->getResponse($objects);

       // Cria array de onde encontrar a tag tr (site dos correios e em tabelas)
       preg_match_all("@<td>(.*)</td>@", $data, $arr, PREG_PATTERN_ORDER);
       // Nessa "engenharia" todos os dados necessários já existem nesse índice
       $data = $arr[0][0];
       // Cria um delimitador para cada 'field' que teremos em cada objeto
       $data = preg_replace("/<td>/", "||", $data);
       // Remove todas as tags menos a tr, que delimita os objetos
       $data = strip_tags($data, '<tr>');
       $items = explode('<tr>', $data);

       /**
        * Cria os fields de cada objeto
        */
       foreach ($items as $item) {
           // Termina de limpar as tags
           $object = (rtrim(strip_tags($item)));
           // Separa os fields usando o delimitador
           $fields = collect(explode('||', $object))->except(0);

           if ($fields->count() == 3) {
               $object = [
                   'codigo'     => $this->cleanAndFormart($fields->get(1)),
                   'situacao'   => $this->cleanAndFormart($fields->get(2)),
                   'local'      => $this->cleanAndFormart(explode(' ', $fields->get(3), 2)[1]),
                   'data'       => $this->cleanAndFormart(explode(' ', $fields->get(3), 2)[0])
               ];
           } else if ($fields->count() == 1) {
               $object = [
                   'codigo'     => $this->cleanAndFormart(explode(' ', $fields->get(1), 2)[0]),
                   'situacao'   => 'Objeto ainda não consta no sistema',
                   'local'      => null,
                   'data'       => null
               ];
           } else {
               $object = [];
           }

           $objects_list[$object['codigo']] = $object;

       }
       return $objects_list;
   }

    /**
     * Página dos correiso retorna windows-1252,
     * os acentos estão em chars html
     * e existem espaços nos dados da tabela
     *
     * @param string $value
     * @return string
     */
   private function cleanAndFormart(string $value)
   {
       return rtrim(
           html_entity_decode(utf8_encode($value), 0, 'UTF-8')
       );
   }
}