<?php
namespace App\Neomerx\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Neomerx\JsonApi\Document\Link;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;
use Neomerx\JsonApi\Encoder\Parameters\EncodingParameters;

class JsonApiTransform
{
    public function transform($class, $objects, $url, $include = null, $encoderp = null)
    {

        // Me fijo si es un objeto de tipo paginación y pongo paginate true
        $option = null;
        if (is_a($objects, LengthAwarePaginator::class)) {
            $option = 'paginate';
        } elseif (is_a($objects, Collection::class)) {
            $option = 'collection';
        } elseif (is_a($objects, $class)) {
            $option = 'object';
        } else {
            throw new \Exception('El objeto no es válido para la clase JsonApiTransform '.$class);
        }

        $options = [];
        $isInclude = false;
        // Si tengo includes en la petición
        if ($include != null) {
            $isInclude = true;
            // Llamo a la función, para convertir  los include que vienen por get en array bien formado
            // y además validar que no manden cualquier valor
            $includeEncoderArray = $this->includeValidate($class, $include);

            if (empty($include)) {
                throw new \Exception('El elemento `include` tiene un objeto vacío.');
            }

            // Mando los parámetros para include
            $options = new EncodingParameters(
                $includeEncoderArray,
                []
            );
        }


        // Creo las relaciones que puedo llegar a obtener de los arrays
        $result = '';
        if (is_array($url)) {
            foreach ($url as $key => $value) {
                $result = $result.'/'.$key.'/'.$value;
            }
            $result = url('/').'/merx'.$result;
        } else {
            $result = url('/')."/merx".$url;
        }

        $url = $result;

        $encoderArray = (new $class)::ENCODER;
        $encoder = Encoder::instance($encoderArray, new EncoderOptions(JSON_PRETTY_PRINT, $url));

        $result = null;

        // Si paginado es verdadero
        if ($option == 'paginate') {
            // Creo los metas para la paginación
            $meta = [
                'total_resources' => $objects->total(),
                'resources_per_page' => (int) $objects->perPage(),
                'page' => $objects->currentPage(),
            ];
            // Creo los links de paginación
            $links = [
                'first' => new Link($objects->url(1).'', null, true),
                'last' => new Link($objects->url($objects->lastPage()).'', null, true),
                'prev' => new Link($objects->previousPageUrl().'', null, true),
                'next' => new Link($objects->nextPageUrl().'', null, true),
            ];

            // Esto se hace para convertir una colección en un array y que lo entienda
            // la librería de json
            $objects = $objects->toArrayObjects();

            if ($isInclude) {
                $result = $encoder
                    ->withLinks($links)
                    ->withMeta($meta)
                    ->encodeData($objects, $options);
            } else {
                $result = $encoder
                    ->withLinks($links)
                    ->withMeta($meta)
                    ->encodeData($objects);
            }
        } elseif ($option == 'collection') {
            // Esto se hace para convertir una colección en un array y que lo entienda
            // la librería de json
            $GLOBALS['isCollection'] = 1;
            $objects = $objects->toArrayObjects();

            if ($isInclude) {
                $result = $encoder
                    ->encodeData($objects, $options);
            } else {
                $result = $encoder
                    ->encodeData($objects);
            }
        } elseif ($option == 'object') {
            if ($isInclude) {
                $result = $encoder
                    ->encodeData($objects, $options);
            } else {
                $result = $encoder
                    ->encodeData($objects);
            }
        } else {
            throw new \Exception('El problema no es reconocido por JsonApiTransform.');
        }


        return $result;
    }


    private function includeValidate($modelClass, $includeString)
    {

        // Saco los paréntesis, que no me sirven
        $includeString = str_replace(['[', ']'], "", $includeString);

        // Convierto a array lo que viene separado por coma
        $includeArray = explode(',', $includeString);

        // Busco las relaciones que existen en ese modelos
        $relations = (new $modelClass)::RELATIONS;

        // Convierto el array a uno solo que solo tenga las keys del array para después poder buscarlo con in_array
        $relationsKeys = array_keys($relations);

        // Creo un array vacío donde se guardara las relaciones que necesito
        $includeNeomerx = [];
        foreach ($includeArray as $var) {
            // Busco dentro del string si existe la variable que viene por get
            if ($var != '') {
                if (in_array($var, $relationsKeys)) {
                    // Tomo los valores de relations (no los keys)
                    $includeNeomerx[] = $relations[$var];
                } else { // Si el include que me mandaron no es valido exception
                    throw new \Exception('El elemento `include`no es válido.');
                }
            }
        }

        return $includeNeomerx;
    }
}
